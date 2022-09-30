<?php
/*
  plugin Name: マイプラグイン
  Author: takabo
*/

add_action('enqueue_block_editor_assets', function () {
  wp_enqueue_script(
    'myeditor-script',
    plugins_url('myeditor.js', __FILE__)
  );
});
