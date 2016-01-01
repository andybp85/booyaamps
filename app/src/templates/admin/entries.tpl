{extends file="$ParentTemplate"}
{block name=extra-css}
<link rel="stylesheet" href="/bower_components/slick-carousel/slick/slick.css" />
<link rel="stylesheet" href="/bower_components/slick-carousel/slick/slick-theme.css" />
<link rel="stylesheet" href="/bower_components/featherlight/src/featherlight.css" />
{/block}
{block name=mainContent}

    <section role="form">
        <select data-bind="options: entries,
                        optionsText: function(entry){ return entry.title() || 'New' },
                        value: selectedEntry">
        </select>

        <form class="galleryForm" data-bind="submit: save">

            <label for="title">Title</label>
            <input type="text" name="title" data-bind="textInput: selectedEntry().title" required >


            <label for="desc">Description</label>
            <textarea type="text" name="desc" data-bind="textInput: selectedEntry().desc" ></textarea>

            <div id="imgCarousel" data-bind="slideVisible: selectedEntry().media().length > 0,
                                             foreach : { data : selectedEntry().media(), as : 'media' },
                                             slick: selectedEntry().id">
                <div class="slide">
                    <div class="deleteImage" data-bind="click: $parent.deleteFile">X</div>
                    <a href="#" data-bind="attr: { 'data-featherlight' : media.path }">
                        <img data-bind="attr: { 'data-lazy' : media.thumbPath !== null ? media.thumbPath : media.path } ">
                    </a>
                </div>
            </div>

            <div class="imgUpload" data-bind="slideVisible: selectedEntry().id">
                <label for="files[]">Upload Media</label>
                <input id="fileupload" type="file" name="files[]" data-url="/admin/media" multiple>

                <div id="files"></div>
                <div id="progress"><div class="bar" style="width: 0%;"></div></div>
            </div>

            <label for="desc">Page-Specific Styles (+)</label>
            <textarea type="text" name="styles" data-bind="textInput: selectedEntry().pageStyles" ></textarea>

            <input type="hidden" name="page" value="{$page}">
            <input type="hidden" name="entryID" data-bind="value: selectedEntry().id">
            <input type="submit" value="Save">
            <div class="success" >Success!</div>

        </form>
    </section>

    <aside id="entryStatus" role="complementary">
        <form class="publishForm" data-bind="submit: updateStatus">
            <label for="publish">Status</label>
            <select name="publish" data-bind="value: selectedEntry().publish"> 
                <option value="draft">Draft</option>
                <option value="publish">Publish</option>
            </select>

            <input type="submit" value="Update">
            <div class="success" >Success!</div>
        </form>
    </aside>

{/block}

{block name=scripts}
<script id="pageModule">
    require(['/scripts/modules/adminEntrys.js']);
</script>
{/block}

