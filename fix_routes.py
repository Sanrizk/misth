import re

with open('routes/web.php', 'r') as f:
    content = f.read()

content = content.replace(
    "Route::resource('purchases', PurchaseController::class)->except(['create', 'edit', 'update']);",
    "Route::resource('purchases', PurchaseController::class)->except(['create', 'edit']);"
)

with open('routes/web.php', 'w') as f:
    f.write(content)
