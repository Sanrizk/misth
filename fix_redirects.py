import re

files = [
    'app/Http/Controllers/MaintenanceLogController.php',
    'app/Http/Controllers/WaterQualityLogController.php',
    'app/Http/Controllers/HarvestController.php'
]

for file in files:
    with open(file, 'r') as f:
        content = f.read()
    
    # Replace single line
    content = re.sub(r"redirect\(\)->route\('[a-z-]+\.index'\)", 'redirect()->back()', content)
    
    # Replace multiline
    content = re.sub(r"redirect\(\)\s*->route\('[a-z-]+\.index'\)", 'redirect()->back()', content)
    
    with open(file, 'w') as f:
        f.write(content)
