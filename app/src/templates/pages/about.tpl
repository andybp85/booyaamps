{extends $ParentTemplate}
{block name=main-content}
<main role="main" data-page="{$page}" >
            <div>
<h1>About</h1>

            </div>
        </main>

{/block}

{block name=scripts}
<script id="pageModule">
    require(['/scripts/modules/main.js']);
</script>
{/block}
