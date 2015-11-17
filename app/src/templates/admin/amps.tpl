{extends file="$ParentTemplate"}
{block name=extra-css}
<link rel="stylesheet" href="/bower_components/slick-carousel/slick/slick.css" />
<link rel="stylesheet" href="/bower_components/slick-carousel/slick/slick-theme.css" />
{/block}
{block name=main-admin-content}

    <section role="form">
        <select data-bind="options: amps,
                        optionsText: function(amp){ return amp.title() || 'New' },
                        value: selectedEntry">
        </select>

        <form class="galleryForm" data-bind="submit: save">

            <label for="title">Title</label>
            <input type="text" name="title" data-bind="textInput: selectedEntry().title" required >


            <label for="desc">Description</label>
            <textarea type="text" name="desc" data-bind="textInput: selectedEntry().desc" ></textarea>

            <div id="imgCarousel" data-bind="slideVisible: selectedEntry().paths().length > 0,
                                             foreach : { data : selectedEntry().paths(), as : 'path' },
                                             slick: selectedEntry().paths()">
                <div class="slide"><img data-bind="attr: { src : path } "></div>
            </div>

            <div class="imgUpload" data-bind="slideVisible: selectedEntry().id">
                <label for="files[]">Upload Media</label>
                <input id="fileupload" type="file" name="files[]" data-url="/admin/media" multiple>

                <div id="files"></div>
                <div id="progress"><div class="bar" style="width: 0%;"></div></div>
            </div>

            <label for="desc">Page-Specific Styles (+)</label>
            <textarea type="text" name="styles" data-bind="textInput: selectedEntry().pageStyles" ></textarea>

            <input type="hidden" name="page" value="amp">
            <input type="hidden" name="entryID" data-bind="value: selectedEntry().id">
            <input type="submit" value="Save">
            <div id="success" >Success!</div>

        </form>
    </section>

    <aside id="entryStatus" role="complementary">
        <label for="published">Status</label>
        <select name="published" data-bind="value: selectedEntry().published"> 
            <option value="0">Draft</option>
            <option value="1">Publish</option>
        </select>

        <button>Update</button>
    </aside>

{/block}

{block name=scripts}
<script
    data-module="/scripts/modules/adminEntrys.js"
    data-main="/scripts/common.js"
    src="/scripts/require.js">
</script>
{/block}

