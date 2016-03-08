{extends file="$ParentTemplate"}
{block name=extra-css}
<link rel="stylesheet" href="/bower_components/codemirror/lib/codemirror.css" />
{/block}

{block name=mainContent}
<form action="/admin/users" method="post">
<label>Username</label>
<input type="text" name="user">
<label>Password</label>
<input type="text" name="password">
<input type="submit">
</form>
{/block}

{block name=scripts}
<script id="pageModule">
    require(['/scripts/modules/adminEditor.js']);
</script>
{/block}
