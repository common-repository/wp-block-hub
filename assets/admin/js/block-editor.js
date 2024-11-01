


function wpblockhubImportButton() {

	var node = document.querySelector('.edit-post-header-toolbar');
	var importEl = document.createElement('div');
	var html = '<div class="wpblockhub-import-container">';
	html += '<button id="wpblockhub-import-btn" class="components-button components-icon-button"><span' +
		' class="dashicons' +
		' dashicons-admin-appearance"></span></button>';
	html += '<div class="item-list-wrap"></div>';
	html += '</div>';
	importEl.innerHTML = html;
	node.appendChild(importEl);
}

document.addEventListener("DOMContentLoaded", wpblockhubImportButton);