import re

def fix_modal(file_path):
    with open(file_path, 'r') as f:
        content = f.read()

    # Find the modal variable name (showModal, showAddModal, showStatusModal)
    # We will do this by replacing the specific HTML structure
    
    # 1. Replace the backdrop structure
    pattern = re.compile(r'<div x-show="([a-zA-Z0-9_]+)" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">\s*<div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">\s*<div class="fixed inset-0 transition-opacity bg-gray-500 opacity-75" aria-hidden="true" @click="\1 = false"></div>\s*<div class="inline-block w-full (max-w-[a-z0-9]+) overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-xl sm:my-8 sm:align-middle">')
    
    def replacer(match):
        var_name = match.group(1)
        max_w = match.group(2)
        return f"""<div x-cloak x-show="{var_name}" x-transition x-init="$watch('{var_name}', val => document.body.style.overflow = val ? 'hidden' : '')"
             class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4"
             @click.self="{var_name} = false">
            <div class="bg-white rounded-2xl w-full {max_w} max-h-[90vh] overflow-y-auto shadow-xl flex flex-col">"""
            
    new_content = pattern.sub(replacer, content)
    
    # If substitutions happened, we need to remove ONE closing </div> before </template> for each match
    # Since there are multiple templates in purchases, we do it per template.
    
    # We can just look for the end of the template block and remove one </div>
    # But it's safer to just replace "</div>\n            </div>\n        </div>\n    </template>"
    # with "</div>\n        </div>\n    </template>"
    
    new_content = re.sub(r'</div>\s*</div>\s*</div>\s*</template>', '</div>\n        </div>\n    </template>', new_content)
    
    with open(file_path, 'w') as f:
        f.write(new_content)

fix_modal('resources/views/suppliers/index.blade.php')
fix_modal('resources/views/purchases/index.blade.php')

