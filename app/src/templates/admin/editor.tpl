{extends file="$ParentTemplate"}
{block name=main-admin-content}
<a href='#'>Settings</a>
<textarea id="editor"></textarea>

{/block}

{block name=scripts}
<script
    data-module="scripts/modules/adminEditor.js"
    data-main="scripts/common.js"
    src="scripts/require.js">
</script>
{/block}
