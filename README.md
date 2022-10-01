# セクション7: ブロックエディター用のカスタムブロックを作成しよう

## 75. ブロックエディターとは

+ wp管理画面 => `Settings` => `Site Language` => `日本語` => `Timezone` => `UTC+9` => `Save Changes`<br>

## 76. ブロックエディターに JavaScriptを読み込もう

+ [wp enqueue script](https://wpdocs.osdn.jp/%E9%96%A2%E6%95%B0%E3%83%AA%E3%83%95%E3%82%A1%E3%83%AC%E3%83%B3%E3%82%B9/wp_enqueue_script) <br>

+ [plugins url](https://wpdocs.osdn.jp/%E9%96%A2%E6%95%B0%E3%83%AA%E3%83%95%E3%82%A1%E3%83%AC%E3%83%B3%E3%82%B9/plugins_url) <br>

+ `$ mkdir app/public/wp-content/plugins/my-plugin && touch $_/my-plugin.php`を実行<br>

+ `app/public/wp-content/plugins/my-plugin/my-plugin.php`を編集<br>

```php:my-plugin.php
<?php
/*
  plugin Name: マイプラグイン
  Author: takabo
*/
```

+ wp管理画面 => `プラグイン` => `有効化`<br>

+ `app/public/wp-content/plugins/my-plugin/my-plugin.php`を編集<br>

```php:my-plugin.php
<?php
/*
  plugin Name: マイプラグイン
  Author: takabo
*/

add_action('enqueue_block_editor_assets', function () {
  wp_enqueue_script(
    'myeditor-script', // id属性が入るので一意性にする id="myeditor-script-js"となる
    plugins_url('myeditor.js', __FILE__)
  );
});
```

+ `$ touch app/public/wp-content/plugins/my-plugin/myeditor.js`を実行<br>

+ `app/public/wp-content/plugins/my-plugin/myeditor.js`を編集<br>

```js:myeditor.js
alert('JavaScriptが読み込まれました')
```

+ wp管理画面 => `投稿` => `新規追加` => 作成したアラートが表示される<br>

## 77. ブロックに独自のスタイルを追加しよう

+ wp管理画面 => `投稿` => `新規追加` => `+`アイコンをクリック => `すべて表示` => `引用` => `引用を追加` => `ここは引用です。`と入力 => `引用元を追加` => `ともすた`と入力 => `公開` => `公開` => `投稿を表示`<br>

+ 上記を編集　=> `引用投稿欄`をクリック => `B`をクリック => `更新` => `投稿を表示` => `太くなる`<br>

+ `app/public/wp-content/plugins/my-plugin/myeditor.js`を編集<br>

```js:myeditor.js
wp.blocks.registerBlockStyle('core/quote', {
  name: 'blue',
  label: 'ブルー'
});
```

+ `wp-content/plugins/my-plugin/my-plugin.php`を編集<br>

```php:my-plugin.php
<?php
/*
  plugin Name: マイプラグイン
  Author: takabo
*/

add_action('enqueue_block_editor_assets', function () {
  wp_enqueue_script(
    'myeditor-script',
    plugins_url('myeditor.js', __FILE__),
    ['wp-blocks'] // 追加
  );
});
```

+ wp管理画面 => 先ほどの投稿編集画面をリロードする<br>

+ キャッシュが残っている場合は、`Command + Shift + R`でリロードする<br>

+ 別の引用ブロックを作成する => `引用を追加` => `新しいスタイルです。` => `引用元を追加` => `ともすた`<br>

+ `''`をクリックして => `ブルー`を選択<br>

+ 参考: [wp enqueue script](https://wpdocs.osdn.jp/%E9%96%A2%E6%95%B0%E3%83%AA%E3%83%95%E3%82%A1%E3%83%AC%E3%83%B3%E3%82%B9/wp_enqueue_script) <br>

## 79. カスタムブロック制作の環境を作ろう

+ [ブロックエディターハンドブック](https://ja.wordpress.org/team/handbook/block-editor/) <br>

+ [開発ガイド](https://ja.wordpress.org/team/handbook/block-editor/how-to-guides/) <br>

+ [create-block パッケージのドキュメント](https://ja.wordpress.org/team/handbook/block-editor/reference-guides/packages/packages-create-block/) <br>
