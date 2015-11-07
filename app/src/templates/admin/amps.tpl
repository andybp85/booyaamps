{extends file="$ParentTemplate"}
{block name=main-admin-content}

    <select data-bind="options: amps,
                       optionsText: function(amp){ return amp.title() || 'New' },
                       value: selectedAmp">
    </select>

    <form class="galleryForm" data-bind="submit: save">

        <label for="title">Title</label>
        <input type="text" name="title" data-bind="textInput: selectedAmp().title" >

        <label for="desc">Description</label>
        <textarea type="text" name="desc" data-bind="textInput: selectedAmp().desc" ></textarea>


        <input type="submit"> 
        <div id="success" >Success!</div>

    </form>

{/block}

{block name=scripts}
<script
    data-module="/scripts/modules/adminAmps.js"
    data-main="/scripts/common.js"
    src="/scripts/require.js">
</script>
{/block}

