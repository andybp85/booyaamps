{extends file="$ParentTemplate"}
{block name=extra-css}
<link rel="stylesheet" href="/bower_components/codemirror/lib/codemirror.css" />
{/block}

{block name=mainContent}
<div class="success" >Success!</div>
<h2 id="cmepath"></h2>
<a id="cmesave" href='#'>Save</a>
<a id="cmeopen" href='#'>Open</a>
<a id="cmesettings" href='#'>Settings</a>
<textarea id="editor"></textarea>

{/block}

{block name=scripts}
<script id="pageModule">
    require(['/scripts/modules/adminEditor.js']);
</script>
{/block}
