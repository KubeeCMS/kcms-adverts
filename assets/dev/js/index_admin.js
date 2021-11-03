import '../css/_ning_admin.css';
import '../css/_ning_uploader.css';
import '../css/dropzone.css';
import '../../packages/coloring_pick/jquery.coloring-pick.min.js.css';
import '../../../include/widgets/chosen/chosen.css';
import '../../../include/widgets/checkbox/checkbox.css';


import Adning_global from './_ning_admin.js';
import './_ning_uploader.js';
import 'dropzone';
import '../../packages/coloring_pick/jquery.coloring-pick.min.js';
import '../../../include/widgets/tooltipster/tooltipster.bundle.min.js';
import '../../../include/widgets/chosen/chosen.jquery.min.js';
import '../../../include/widgets/chosen/jquery-chosen-sortable.min.js';

import codemirror from 'codemirror';
import 'codemirror/mode/javascript/javascript';
import 'codemirror/mode/css/css';
import 'codemirror/mode/htmlmixed/htmlmixed';
import 'codemirror/lib/codemirror.css';
import '../css/cm_github.css';

window._ning_codemirror = codemirror;
window.Adning_global = Adning_global.Adning_global;


jQuery(document).ready(function($) {
    
    /*
     * ADD Codemirror to all code textareas
    */
    window.Adning_global.load_code_editor();
	

	$('#banner_content').on('change', function(){
		//console.log( $(this).val() );
		var render_preview = $('#dont_render_preview_code').prop("checked");
		if( render_preview ){
			$("._ning_cont").find('._ning_inner').html( $(this).val() );
		}
		
		// CodeMirror
		$(this).parent().find('.CodeMirror').remove();
		var height = $(this)[0].getAttribute("data-height") !== null ? $(this)[0].getAttribute("data-height") : '150px';
		
        var codearea = $(this)[0];
        var editor = codemirror.fromTextArea(codearea, {
            lineNumbers : true,
            mode: $(this)[0].getAttribute("data-lang"),
            theme: "github"
		});
		editor.setSize("100%",height);
		editor.on("change", function() {
			//console.log('Codemirror change');
			
			if( render_preview ){
				$("._ning_cont").find('._ning_inner').html( editor.getValue() );
			}
			console.log('banner content changed');
		});
	});

});

/*function load_code_editor(){

	var code_editors = document.querySelectorAll(".code_editor");

   	for (var i = 0; i < code_editors.length; i++) {
		
		var height = code_editors[i].getAttribute("data-height") !== null ? code_editors[i].getAttribute("data-height") : '150px';
		
	   	var editor = codemirror.fromTextArea(code_editors[i], {
			lineNumbers : true,
			mode: code_editors[i].getAttribute("data-lang"),
			theme: "github",
	   	});
		editor.setSize("100%",height);

		if( code_editors[i].getAttribute('id') === 'banner_content' ){
			editor.on("change", function() {
				console.log('Codemirror change');
				var render_preview = $('#dont_render_preview_code').prop("checked");
				if( render_preview ){
					$("._ning_cont").find('._ning_inner').html( editor.getValue() );
				}
			});
		}
	}
}*/