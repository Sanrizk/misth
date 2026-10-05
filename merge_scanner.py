import re

with open('resources/views/transactions/index.blade.php', 'r') as f:
    tx_content = f.read()

with open('resources/views/scanner/index.blade.php', 'r') as f:
    scan_content = f.read()

# Extract scanner UI part from scanner/index.blade.php
# It's between <div class="max-w-lg mx-auto space-y-4" and @endsection
scan_ui_match = re.search(r'<div class="max-w-lg mx-auto space-y-4"[^>]*>([\s\S]*?)</div>\n\n@endsection', scan_content)
scan_ui = scan_ui_match.group(0).replace('@endsection', '').strip()

# Change the form in transactions/index.blade.php so that it uses scannerApp
tx_content = tx_content.replace('x-data="barcodeSearch()"', 'x-data="scannerApp()"')

# Insert scan_ui directly beneath the table </div>
# The table ends at </div>\n\n@endsection
# Wait, it ends at </div>\n\n@endsection inside tx_content. Let's find it.

table_end = """    <div class="px-4 py-3 border-t border-gray-100">
        {{ $transactions->links('pagination::tailwind') }}
    </div>
</div>"""

if table_end in tx_content:
    new_ui = f"""
    <div class="px-4 py-3 border-t border-gray-100">
        {{{{ $transactions->links('pagination::tailwind') }}}}
    </div>
</div>

<div class="mt-8" x-show="showScannerInterface" x-cloak>
{scan_ui}
</div>
"""
    tx_content = tx_content.replace(table_end, new_ui)

# Now, we need to extract the scripts and styles from scanner/index.blade.php
script_style_match = re.search(r'@section\(\'scripts\'\)\n([\s\S]*?)@endsection', scan_content)
scan_scripts = script_style_match.group(1).strip()

# Replace scanner route references in the script
scan_scripts = scan_scripts.replace("{{ route('scanner.find') }}", "{{ route('transactions.scanner.find') }}")
scan_scripts = scan_scripts.replace("`/scanner/confirm/${this.transaction.id}`", "`/transactions/scanner/confirm/${this.transaction.id}`")

# Modify scannerApp to integrate with the transactions page
# Add showScannerInterface: false, and modify startScanner
scan_scripts = scan_scripts.replace("mode: 'camera',", "mode: 'camera',\n        showScannerInterface: false,")
scan_scripts = scan_scripts.replace("this.$nextTick(() => this.startCamera());", "// don't auto start")
# Add startScanner which opens the interface and starts camera
scan_scripts = scan_scripts.replace("async startCamera() {", "async startScanner() {\n            this.showScannerInterface = true;\n            this.startCamera();\n        },\n\n        async startCamera() {")

# Replace barcodeSearch script in tx_content with scan_scripts
old_script_match = re.search(r'@section\(\'scripts\'\)\n([\s\S]*?)@endsection', tx_content)
if old_script_match:
    tx_content = tx_content.replace(old_script_match.group(1), scan_scripts + '\n')

with open('resources/views/transactions/index.blade.php', 'w') as f:
    f.write(tx_content)

print("Merge completed")
