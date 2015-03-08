<?php
/*
 * Larry bird is here to help
 * He's enqueud on every page
 */
add_thickbox();
?>
<a href="#TB_inline?width=600&height=550&inlineId=larry-bird" class="thickbox larry-bird"></a>

<div id="larry-bird" style="display:none;">
    <div id="larry-bird-content">
        <div id="title-and-search">
            <h1>SHHHHHH</h1>
            <h3>Just start typing</h3>
            <form id="larry-bird-form" action="" method="post">
                <input type="text" id="larry-input" list="larry-bird-form-list" />
                <datalist id="larry-bird-form-list"></datalist>
                <button type="submit" id="for-the-three">For the Three!</button>
            </form>
        </div>
        <!-- js target for resutls -->
        <ul id="smart-results"></ul>
        <ul id="backup-results"></ul>

    </div>
</div>

<style>
    #larry-bird-content {
        top: 0;
        width: 100%;
        height: 100%;
        background-image: url( '<?php echo plugins_url( "smartkeys/images/larry_bird.jpg" ); ?>' );
        background-size: cover;
        position: absolute;
    }

    #title-and-search {
        text-align: center;
    }

    #larry-input {
        width: 50%;
    }

    #smart-results, #backup-results {
        margin: 1.5em .5em .5em;
    }
    .smart-result {
        display: inline-block;
        list-style-type: none;
        font-size: 1.2em;
        font-weight: bold;
        margin: 1em 1em ;
    }

    .smart-result a {
        color: #000 !important;
    }
    .smart-result a:hover {
        color: #333333 !important;
    }

    #TB_window {
        height: 80% !important;
        top: 10% !important;
        margin-top: 0 !important;
    }

    #TB_ajaxContent {
        width: 100% !important;
        height: 100% !important;
        padding: 0 !important;
        background: none !important;
        overflow: unset !important;
    }
</style>