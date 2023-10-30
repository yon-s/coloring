(function() {
  const clickcount  = null;
  tinymce.create('tinymce.plugins.original_tinymce_button', {
    init: function(ed) {
      ed.addButton('insert_linkbtn', {
        text: 'リンクボタンをつくる',
        type: 'button',
        cmd: 'linkbtn_popup',
      });
      ed.addButton('insert_caution', {
        text: '注意文をつくる',
        type: 'button',
        onclick: function() {
          ed.insertContent('<div class="content__txt"><p class="caution__list-item">ここに文字</p></div>');
        }
      });
      ed.addButton('insert_caution-no', {
        text: '(※数字)つける',
        type: 'button',
        onclick: function() {
          ed.insertContent('<span class="caution__no">&#40;&#8251;ここに数字&#41;</span>');
        }
      });
      ed.addButton('insert_marker', {
        text: 'マーカーつける',
        type: 'button',
        onclick: function() {
          const html_tag = document.getElementById('mceu_39').innerHTML.indexOf('span');
          const select =ed.selection.getContent();
          if(html_tag === -1){	
            ed.insertContent(`<span class="content__marker">${select}</span>`);
          }else{
            ed.insertContent(select);
          }
        }
      });
      ed.addButton('insert_chek-mark-list', {
        text: 'チェックマーク付きリストを作成する',
        type: 'button',
        onclick: function() {
            ed.insertContent(`<ul class="content__check-list"><li class="content__check-item-not">テキストを選択してチェックマークボタンを押す</li></ul>`);
        }
      });
      ed.addButton('insert_check-item', {
        text: 'チェックマークをつける',
        type: 'button',
        onclick: function() {
          const html_tag = document.getElementById('mceu_39').innerHTML.indexOf('li');
          const select =ed.selection.getContent();
          if(html_tag === -1){	
            ed.insertContent(`<li class="content__check-item">${select}</li>`);
          }else{
            ed.insertContent(select);
          }
        }
      });
      ed.addButton('insert_check-item-not', {
        text: 'チェックマークを外す',
        type: 'button',
        onclick: function() {
          const html_tag = document.getElementById('mceu_39').innerHTML.indexOf('li');
          const select =ed.selection.getContent();
          if(html_tag === -1){	
            ed.insertContent(`<li class="content__check-item-not">${select}</li>`);
          }else{
            ed.insertContent(select);
          }
        }
      });
      ed.addButton('insert_img_desc', {
        text: '画像に説明文をつける',
        type: 'button',
        onclick: function() {
          const select =ed.selection.getContent();
          ed.insertContent(`${select}<p class="content__figcaption">ここにテキスト</p>`);
        }
      });
      ed.addButton('insert_img-mobile', {
        text: 'スマホサイズの画像にする',
        type: 'button',
        onclick: function() {
          const select =ed.selection.getContent();
          ed.insertContent(`<div class="content__img-mobile">${select}</div>`);
        }
      });
      ed.addButton('insert_table-sp', {
        text: 'スマホで横にスクロールさせる',
        type: 'button',
        onclick: function() {
          const select =ed.selection.getContent();
          ed.insertContent(`<div class="content__table-outer"><p class="content__table-info">横にスクロールしてご覧ください。</p>${select}</div>`);
        }
      });
      ed.addButton('insert_gif-img', {
        text: 'gif画像をあとで読み込ませる',
        type: 'button',
        onclick: function() {
          const select = ed.selection.getContent();
          const select_ret = select.replace('<img', '<!--<img').replace('>', '>-->');
          ed.insertContent(`<div class="content__link-btn-outer"><a class="content__gifopen" >動画(gif画像)で見る</a></div>
          <div class="content__gif-src">${select_ret}</div>`);
        }
      });
      ed.addButton('insert_update-day', {
        text: '更新情報',
        type: 'button',
        onclick: function() {
          ed.insertContent(`<div class="content__update-info"><span class="content__update-info-ttl">更新情報&#058;&nbsp;</span>ここにテキスト</div>`);
        }
      });
      ed.addButton('insert_point', {
        text: 'ポイントをまとめる',
        type: 'button',
        onclick: function() {
          ed.insertContent(`<div class="content__point"><p class="content__point--ttl">ここがポイント</p><ol class="content__ol"><li class="content__li">ここにテキスト</li></ol></div>`);
        }
      });
      ed.addButton('insert_raw_material', {
        text: '原材料を入力',
        type: 'button',
        onclick: function() {
          ed.insertContent(`<div class="content__point">
          <p class="content__point--ttl">原材料</p>
          <p class="content__txt">ここにテキスト</p></div>`);
        }
      });
      ed.addButton('insert_list_price', {
        text: '価格リストを作成',
        type: 'button',
        onclick: function() {
          ed.insertContent(`<div class="content__aside-main--col">
          <dl class="info__list">
          <dt class="info__list-ttl">税抜&nbsp;:</dt>
          <dd class="info__list-item">¥</dd>
          </dl>
          <h5 class="content__ttl5--punctuation" id="outline__4_1_1_1">税込</h5>
          <dl class="info__list">
          <dt class="info__list-ttl">テイクアウト&nbsp;:</dt>
          <dd class="info__list-item">¥</dd>
          </dl>
          <dl class="info__list">
          <dt class="info__list-ttl">イートイン&nbsp;:</dt>
          <dd class="info__list-item">¥</dd>
          </dl>
          </div>`);
        }
      });
      ed.addButton('insert_post_link', {
        text: '関連記事のリンクをつくる',
        type: 'button',
        cmd: 'url_popup',
      });
      ed.addButton('insert_blockquote', {
        text: '引用文を追加する',
        type: 'button',
        cmd: 'blockquote_popup',
      });
      ed.addButton('insert_product-link', {
        text: '商品紹介を追加する',
        type: 'button',
        cmd: 'product-link_popup',
      });
      ed.addButton('insert_app-btn', {
        text: 'スマホアプリのリンクボタンを追加',
        type: 'button',
        cmd: 'link_app',
      });
      ed.addButton('insert_cal-btn', {
        text: '栄養成分を追加',
        type: 'button',
        cmd: 'add_cal',
      });
      ed.addCommand('linkbtn_popup', function (val) {
        let text = '';
        let url = '';
        if (val && val.text) text = val.text;
        if (val && val.url) url = val.url;
        ed.windowManager.open({
          title: 'リンクボタンポップアップ',
          body: [
            {
              type: 'textbox',
              name: 'url',
              label: 'URL',
              value: url
            },
            {
              type: 'textbox',
              name: 'text',
              label: 'リンクするテキスト',
              value: text
            }
          ],
          onsubmit: function( e ) {
            ed.insertContent(`<div class="content__link-btn-outer--center"><a href="${e.data.url}" target="_blank" rel="noopener" class="content__link-btn">${e.data.text}</a></div>`);
          }
        });
      });
      ed.addCommand('url_popup', function (val) {
        let text = '';
        let url = '';
        if (val && val.text) text = val.text;
        if (val && val.url) url = val.url;
        ed.windowManager.open({
          title: 'リンクボタンポップアップ',
          body: [
            {
              type: 'textbox',
              name: 'text',
              label: '記事の説明',
              value: text
            },
            {
              type: 'textbox',
              name: 'url',
              label: 'URL',
              with: 640,
              placeholder:'２つ以上は半角スペースを入れる',
              value: url
            }
          ],
          onsubmit: function( e ) {
            if(e.data.text) {linkText = e.data.text;}else{linkText =  '関連記事'}
             ed.insertContent(`<aside class="content__inside">
             <div class="content__inside-ttl-outer"><p class="content__inside-ttl">${linkText}</p></div><div>[post_link ${e.data.url}]</div></aside>`);
          }
        });
      });
      ed.addCommand('blockquote_popup', function (val) {
        let text = '';
        let url = '';
        let blockquote = '';
        if (val && val.text) text = val.text;
        if (val && val.url) url = val.url;
        if (val && val.blockquote) blockquote = val.blockquote;
        ed.windowManager.open({
          title: '引用文ポップアップ',
          body: [
            {
              type: 'textbox',
              name: 'url',
              label: 'URL',
              value: url
            },
            {
              type: 'textbox',
              name: 'text',
              label: 'リンクするテキスト',
              value: text
            },
            {
              type: 'textbox',
              name: 'blockquote',
              label: '引用元',
              value: blockquote
            }
          ],
          onsubmit: function( e ) {
            ed.insertContent(`<blockquote class="content__blockquote"><i class="fas fa-chevron-circle-right"></i>${e.data.text}<br>引用元&#058;&nbsp;<cite class="content__cite"><a href="${e.data.url}" target="_blank" rel="noopener" class="content__cite-link">${e.data.blockquote}</a></cite></blockquote>`);
          }
        });
      });
      ed.addCommand('product-link_popup', function (val) {
        let img = '';
        let figcaption = '';
        let product_name = '';
        let price = '';
        let link_amazon = '';
        let link_rakuten = '';
        let link_yahoo = '';
        let link_etc = '';
        if (val && val.img) img = val.img;
        if (val && val.figcaption) figcaption = val.figcaption;
        if (val && val.product_name) product_name = val.product_name;
        if (val && val.price) price = val.price;
        if (val && val.link_amazon) link_amazon = val.link_amazon;
        if (val && val.link_rakuten) link_rakuten = val.link_rakuten;
        if (val && val.link_yahoo) link_yahoo = val.link_yahoo;
        if (val && val.link_etc) link_etc = val.link_etc;
        ed.windowManager.open({
          title: '商品紹介ポップアップ',
          body: [
            {
              type: 'textbox',
              name: 'img',
              label: '商品画像HTML',
              value: img
            },
            {
              type: 'textbox',
              name: 'figcaption',
              label: '画像引用元',
              value: figcaption
            },
            {
              type: 'textbox',
              name: 'product_name',
              label: '商品名',
              value: product_name
            },
            {
              type: 'textbox',
              name: 'price',
              label: '価格',
              value: price
            },
            {
              type: 'textbox',
              name: 'link_amazon',
              label: 'アマゾンのリンク',
              value: link_amazon
            },
            {
              type: 'textbox',
              name: 'link_rakuten',
              label: '楽天市場のリンク',
              value: link_rakuten
            },
            {
              type: 'textbox',
              name: 'link_yahoo',
              label: 'ヤフーショッピングのリンク',
              value: link_yahoo
            },
            {
              type: 'textbox',
              name: 'link_etc',
              label: 'その他のリンク',
              value: link_etc
            },
          ],
          onsubmit: function( e ) {
            let ul = '';
            let ul_end= '';
            let li = '';
            let li_end = '';
            let amazon_link = '';
            let rakuten_link = '';
            let yahoo_link = '';
            let etc_link = '';
            if(e.data.link_amazon || e.data.link_rakuten || e.data.link_yahoo || e.data.link_etc){
              ul = '<ul class="buy__button--column">';
              ul_end = '</ul>';
              li = '<li class="buy__button-link--column"><a class="buy__link--border" ';
              li_end = '</a></li>';
            } 
            if(e.data.link_amazon) amazon_link = `${li} href="${e.data.link_amazon}">Amazon${li_end}`;
            if(e.data.link_rakuten) rakuten_link = `${li} href="${e.data.link_rakuten}">楽天市場${li_end}`;
            if(e.data.link_yahoo) yahoo_link = `${li} href="${e.data.link_yahoo}">ヤフーショッピング${li_end}`;
            if(e.data.link_etc) etc_link = `${li} href="${e.data.link_etc}">詳細情報${li_end}`;
            ed.insertContent(`<article class="product__list-item">
            <div class="product__img-outer">
            ${e.data.img}
            <p class="product__img-figcaption">${e.data.figcaption}</p>
            </div>
            <div class="product__content">
            <div class="product__info">
            <p class="product__name">${e.data.product_name}</p>
            <p class="product__price">&yen;${Number(e.data.price).toLocaleString()}</p>
            </div>
            ${ul}
            ${amazon_link}
            ${rakuten_link}
            ${yahoo_link}
            ${etc_link}
            ${ul_end}
            </div>
            </article>`);
          }
        });
      });
      ed.addCommand('link_app', function (val) {
        let url_android = '';
        let url_ios = '';
        if (val && val.url_android) url_android = val.url_android;
        if (val && val.url_ios) url_ios = val.url_ios;
        ed.windowManager.open({
          title: 'アプリのリンクボタンをつくる',
          body: [
            {
              type: 'textbox',
              name: 'url_android',
              label: 'Google Play URL',
              value: url_android
            },
            {
              type: 'textbox',
              name: 'text',
              label: 'App Store URL',
              value: url_ios
            }
          ],
          onsubmit: function( e ) {
            let domain=window.location.protocol + '//' + window.location.host;
            ed.insertContent(`<ul class="app__button">
            <li class="app__button-link" role="button"><a class="app__link--android" role="noopener noreferrer" href="${e.data.url_android}" target="_blank" rel="noopener"><img src="${domain}/wordpress/wp-content/themes/coloring/img/button/android.png" /></a></li>
            <li class="app__button-link" role="button"><a class="app__link--ios" role="noopener noreferrer" href="${e.data.url_ios}" target="_blank" rel="noopener"><img src="${domain}/wordpress/wp-content/themes/coloring/img/button/ios.png" /></a></li>
         </ul>`);
          }
        });
      });
      ed.addCommand('add_cal', function (val) {
        let unit = '';let energy = '';let protein = '';let fat = '';let carbohydrate = '';let sugar = '';let dietary_fiber = '';let salt = '';let cal_caution = '';
        if (val && val.energy) energy = val.energy;
        if (val && val.unit) unit = val.unit;
        if (val && val.protein) protein = val.protein;
        if (val && val.fat) fat = val.fat;
        if (val && val.carbohydrate) carbohydrate = val.carbohydrate;
        if (val && val.sugar) sugar = val.sugar;
        if (val && val.dietary_fiber) dietary_fiber = val.dietary_fiber;
        if (val && val.salt) salt = val.salt;
        if (val && val.cal_caution) cal_caution = val.cal_caution;
        ed.windowManager.open({
          title: '栄養素ボタンポップアップ',
          body: [
            {
              type: 'textbox',
              name: 'unit',
              label: '〇〇あたり',
              value: unit
            },
            {
              type: 'textbox',
              name: 'energy',
              label: 'エネルギー(cal)',
              value: energy
            },
            {
              type: 'textbox',
              name: 'protein',
              label: 'たんぱく質',
              value: protein
            },
            {
              type: 'textbox',
              name: 'fat',
              label: '脂質',
              value: fat
            },
            {
              type: 'textbox',
              name: 'carbohydrate',
              label: '炭水化物',
              value: carbohydrate
            },
            {
              type: 'textbox',
              name: 'sugar',
              label: '糖質',
              value: sugar
            },
            {
              type: 'textbox',
              name: 'dietary_fiber',
              label: '食物繊維',
              value: dietary_fiber
            },
            {
              type: 'textbox',
              name: 'salt',
              label: '食塩相当量',
              value: salt
            },
            {
              type: 'textbox',
              name: 'cal_caution',
              label: '注意事項',
              value: cal_caution
            },
         ],
          onsubmit: function( e ) {
            let sugar_fiber;let cal_caution_txt;
            if(e.data.sugar && e.data.dietary_fiber){
              sugar_fiber =`<dl class="info__list"><dt class="info__list-ttl">-糖質</dt><dd class="info__list-item">${e.data.sugar}g</dd></dl><dl class="info__list"><dt class="info__list-ttl">-食物繊維</dt><dd class="info__list-item">${e.data.dietary_fiber}g</dd></dl>`;
            }else if(e.data.sugar){
              sugar_fiber =`<dl class="info__list"><dt class="info__list-ttl">-糖質</dt><dd class="info__list-item">${e.data.sugar}g</dd></dl>`;
            }else if(e.data.dietary_fiber){
              sugar_fiber =`<dl class="info__list"><dt class="info__list-ttl">-食物繊維</dt><dd class="info__list-item">${e.data.dietary_fiber}g</dd></dl>`;
            }else{
              sugar_fiber = '';
            }
            if(e.data.cal_caution){
              cal_caution_txt =`<div class="content__txt"><p class="caution__list-item">${e.data.cal_caution}</p></div>`;
            }else{
              cal_caution_txt = '';
            }
            ed.insertContent(`<div class="content__point">
            <p class="content__point--ttl">栄養成分表示(${e.data.unit}あたり)</p>
            <dl class="info__list">
               <dt class="info__list-ttl">エネルギー</dt>
               <dd class="info__list-item">${e.data.energy}kcal</dd>
            </dl>
            <dl class="info__list">
               <dt class="info__list-ttl">たんぱく質</dt>
               <dd class="info__list-item">${e.data.protein}g</dd>
            </dl>
            <dl class="info__list">
               <dt class="info__list-ttl">脂質</dt>
               <dd class="info__list-item">${e.data.fat}g</dd>
            </dl>
            <dl class="info__list">
               <dt class="info__list-ttl">炭水化物</dt>
               <dd class="info__list-item">${e.data.carbohydrate}g</dd>
            </dl>
            ${sugar_fiber}
            <dl class="info__list">
               <dt class="info__list-ttl">食塩相当量</dt>
               <dd class="info__list-item">${e.data.salt}g</dd>
            </dl>${cal_caution_txt}
            </div>`);
          }
        });
      });
    },
  });
  tinymce.PluginManager.add('original_tinymce_button_plugin', tinymce.plugins.original_tinymce_button);
})();