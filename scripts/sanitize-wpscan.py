#!/usr/bin/env python3
"""Export an allowlisted summary. Never publish raw WPScan output or credentials."""
import argparse
from datetime import datetime, timezone
import json
import os
from pathlib import Path

parser = argparse.ArgumentParser()
parser.add_argument('raw')
parser.add_argument('output')
parser.add_argument('--browser-user-agent', action='store_true')
parser.add_argument('--api-token-used', action='store_true')
parser.add_argument('--installed-inventory', help='Allowlisted filesystem inventory JSON, never a credential file')
args = parser.parse_args()
raw = json.loads(Path(args.raw).read_text())
slug = ''
if os.environ.get('LK_SECURITY_PRIVATE'):
    slug = json.loads(Path(os.environ['LK_SECURITY_PRIVATE']).read_text()).get('login_slug', '')

def safe(value):
    if isinstance(value, str) and slug:
        return value.replace(slug, '[PRIVATE_LOGIN]')
    return value

def version(value):
    if not isinstance(value, dict):
        return None
    return {key: value[key] for key in ['number', 'status', 'found_by', 'confidence'] if key in value}

def vulnerabilities(value):
    if not isinstance(value, dict):
        return []
    results = []
    for item in value.get('vulnerabilities', []):
        if not isinstance(item, dict):
            continue
        record = {key: item[key] for key in ['id', 'title', 'vuln_type', 'fixed_in', 'cvss', 'verified'] if key in item}
        refs = item.get('references', {})
        if isinstance(refs, dict):
            record['references'] = {key: refs[key] for key in ['cve', 'url', 'wpvulndb'] if key in refs}
        results.append(record)
    return results

api = raw.get('vuln_api', {})
api_success = bool(api.get('plan') and api.get('requests_remaining') is not None and not api.get('error') and not raw.get('scan_aborted'))
api_summary = {
    'token_provided': bool(args.api_token_used or api_success),
    'vulnerability_data_available': api_success,
    'limitation': 'Only detected components/versions are covered; custom code and missing API records are not certified safe.' if api_success else 'No successful vulnerability API result: do not infer absence of vulnerabilities.',
}
for key in ['plan', 'requests_done_during_scan', 'requests_remaining', 'error']:
    if key in api:
        api_summary[key] = safe(api[key])

summary = {
    'tool': 'WPScan', 'tool_version': '3.8.28',
    'scope': safe(raw.get('target_url')), 'start_time': raw.get('start_time'),
    'stop_time': raw.get('stop_time'),
    'start_time_utc': datetime.fromtimestamp(raw['start_time'], timezone.utc).isoformat() if isinstance(raw.get('start_time'), (int, float)) else None,
    'stop_time_utc': datetime.fromtimestamp(raw['stop_time'], timezone.utc).isoformat() if isinstance(raw.get('stop_time'), (int, float)) else None, 'elapsed_seconds': raw.get('elapsed'),
    'requests_done': raw.get('requests_done'), 'cached_requests': raw.get('cached_requests'), 'scan_aborted': safe(raw.get('scan_aborted')),
    'command_without_secrets': 'wpscan --url http://baitapnhom1.local --enumerate vp,vt --plugins-detection passive --max-threads 2 --throttle 100 --no-update --clear-cache --cache-ttl 0 --no-banner --format json --output <PRIVATE_RAW_OUTPUT>',
    'vulnerability_api': api_summary,
    'detected_wordpress': version(raw.get('version')),
    'findings': [{'type': item.get('type'), 'summary': safe(item.get('to_s')), 'found_by': item.get('found_by')} for item in raw.get('interesting_findings', [])],
    'detected_plugins': {name: version(info.get('version')) for name, info in raw.get('plugins', {}).items()},
    'detected_themes': {name: version(info.get('version')) for name, info in raw.get('themes', {}).items()},
}
summary['vulnerabilities'] = {
    'wordpress': vulnerabilities(raw.get('version')),
    'main_theme': vulnerabilities(raw.get('main_theme')),
    'plugins': {name: vulnerabilities(info) for name, info in raw.get('plugins', {}).items()},
    'themes': {name: vulnerabilities(info) for name, info in raw.get('themes', {}).items()},
}
main_theme = raw.get('main_theme')
if isinstance(main_theme, dict):
    summary['main_theme'] = {'slug': main_theme.get('slug'), 'version': version(main_theme.get('version'))}
if args.installed_inventory:
    inventory = json.loads(Path(args.installed_inventory).read_text())
    summary['installed_inventory'] = inventory
    summary['installed_plugins_not_detected'] = [item['slug'] for item in inventory.get('plugins', []) if item['slug'] not in raw.get('plugins', {})]
if 'before' in Path(args.output).name:
    summary['command_without_secrets'] = summary['command_without_secrets'].replace(' --clear-cache --cache-ttl 0', '')
if args.browser_user_agent:
    summary['command_without_secrets'] = summary['command_without_secrets'].replace('--cache-ttl 0', '--cache-ttl 0 --random-user-agent')
    summary['waf_mode_during_scan'] = 'enabled/basic'
summary['exit_code'] = None if raw.get('scan_aborted') else 0
Path(args.output).write_text(json.dumps(summary, ensure_ascii=False, indent=2) + '\n')
