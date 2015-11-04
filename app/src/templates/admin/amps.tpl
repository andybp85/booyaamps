{extends file="$ParentTemplate"}
{block name=main-admin-content}

    <select>
        <option value="new">New</option>
    </select>

    <form class="galleryFrom" data-bind="submit: save">

        <label for="title">Title</label>
        <input type="text" name="title" data-bind="value: title" >

        <label for="desc">Description</label>
        <input type="text" name="desc" data-bind="value: desc" >


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

