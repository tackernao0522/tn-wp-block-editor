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

## 80. カスタムブロックをビルドしてみよう

+ `$ cd app/public/wp-content/plugins/`を実行<br>

+ `$ npx @wordpress/create-block gutenpride(名前はなんでも良い)を実行<br>

+ `$ cd gutenpride/`を実行<br>

+ `$ npm install --save-dev postcss`を実行<br>

+ `$ npm run build`を実行<br>

+ wp管理画面 => `プラグイン` => `インストール済みプラグイン` => `Gutenpride` => `有効化`<br>

+ wp管理画面 => `投稿` => `新規追加` => `+` => `すべて表示` => `Gutenpride` で雛形ができる<br>

## 82. カスタムブロックに編集可能なテキストフィールドを作成しよう

+ `wp-content/plugins/gutenpride/src/edit.js`を編集<br>

```js:edit.js
/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { useBlockProps } from '@wordpress/block-editor';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */
export default function Edit() {
	return (
		<p { ...useBlockProps() }>
			{ __( 'こんにちは', 'gutenpride' ) } // 編集
		</p>
	);
}
```

+ `/gutenpride $ npm run start`を実行(ビルドをリアルタイムに監視)<br>

+ wp管理画面 => 先ほど作成したブロックエディターをリロードしてみる(こんにちはに変わっていればOK)<br>

+ `wp-content/plugins/gutenpride/src/index.js`を編集<br>

```js:index.js
/**
 * Registers a new block provided a unique name and an object defining its behavior.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */
import { registerBlockType } from '@wordpress/blocks'

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * All files containing `style` keyword are bundled together. The code used
 * gets applied both to the front of your site and to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './style.scss'

/**
 * Internal dependencies
 */
import Edit from './edit'
import save from './save'
import metadata from './block.json'

/**
 * Every block starts by registering a new block type definition.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */
registerBlockType(metadata.name, {
  // 追加
  apiVersion: 2,
  attributes: {
    message: {
      type: 'string',
      source: 'text',
      selector: 'div',
      default: '',
    },
  },
  // ここまで

  /**
   * @see ./edit.js
   */
  edit: Edit,

  /**
   * @see ./save.js
   */
  save,
})
```

+ `wp-content/plugins/gutenpride/src/edit.js`を編集<br>

```js:edit.js
/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n'

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { useBlockProps } from '@wordpress/block-editor'
import { TextControl } from '@wordpress/components' // 追加

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss'

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */
// 編集
export default function Edit({ attributes, setAttributes }) {
  return (
    <div {...useBlockProps()}>
      <TextControl
        label={__('Message', 'gutenpride')}
        value={attributes.message}
        onChange={(val) => setAttributes({ message: val })}
      />
    </div>
  )
}
// ここまで
```

+ wp管理画面 => 先ほど作成したブロックエディターをリロードしてみる(ラベルが'Message'のテキストフィールドになっていればOK)<br>

+ まだテキストフィールドに何か入力して公開しても何も表示されない<br>

+ `wp-content/plugins/gutenpride/src/save.js`を編集<br>

```js:save.js
/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { useBlockProps } from '@wordpress/block-editor'

/**
 * The save function defines the way in which the different attributes should
 * be combined into the final markup, which is then serialized by the block
 * editor into `post_content`.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#save
 *
 * @return {WPElement} Element to render.
 */
// 編集
export default function save({ attributes }) {
  return <div {...useBlockProps.save()}>{attributes.message}</div> // selector: 'div'にしたので
}
// ここまで
```

+ wp管理画面 => 先ほど作成したブロックエディターをリロードしてみる(不具合が出る)<br>

+ `オプション` => `Gutenprideを削除` => `+`をクリックして再度 `Gutenpride`を選択する<br>

+ `Message` => `Hello`と入力してみる => `更新` => `投稿を表示` (Hello)とブラウザに表示されていればOK<br>

+ 編集もできるようになっている<br>

+ end<br>