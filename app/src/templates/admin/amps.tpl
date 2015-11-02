{extends file="$ParentTemplate"}
{block name=main-admin-content}

    <select>
        <option value="new">New</option>
    </select>

    <form class="galleryFrom">

        <label for="entry[title]">Title</label>
        <input type="text" name="entry[title]">

        <label for="entry[description]">Description</label>
        <input type="text" name="entry[description]">


        <input type="hidden" name="table" value="amps">
        <input type="submit">

    </form>

{/block}

{block name=scripts}
<script
    data-module="scripts/modules/adminAmps.js"
    data-main="scripts/common.js"
    src="scripts/require.js">
</script>
{/block}

