(()=>{"use strict";var e={n:t=>{var r=t&&t.__esModule?()=>t.default:()=>t;return e.d(r,{a:r}),r},d:(t,r)=>{for(var i in r)e.o(r,i)&&!e.o(t,i)&&Object.defineProperty(t,i,{enumerable:!0,get:r[i]})},o:(e,t)=>Object.prototype.hasOwnProperty.call(e,t)};const t=window.wp.i18n,r=window.wp.blocks,i=window.React,a=window.wp.blockEditor,o=window.wp.components,s=window.wp.apiFetch;var n=e.n(s);const l=window.wp.data,c=window.wp.element,u=({clientId:e,blockType:t,attributes:i})=>{const o=(0,l.useSelect)((t=>t(a.store).getBlock(null!=e?e:"")),[e]),{replaceBlock:s}=(0,l.useDispatch)(a.store);(0,c.useEffect)((()=>{o?.name&&s&&e&&s(e,[(0,r.createBlock)(t,i)])}),[o,s,e,t])},{useSelect:p,dispatch:A}=wp.data,{useEffect:m}=wp.element,g=wp.element.createElement;(0,r.registerBlockType)("image-upload-via-imgur/upload",{title:(0,t.__)("Image Upload via Imgur","image-upload-for-imgur"),description:(0,t.__)("Provides a Gutenberg block to upload an image to imgur via API.","image-upload-for-imgur"),icon:g("img",{src:" data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAJCAYAAAAo/ezGAAABhmlDQ1BJQ0MgcHJvZmlsZQAAKJF9kT1Iw0AcxV9TpX5UHewg4pChFgcLoiKOUsUiWChthVYdTC79giYNSYqLo+BacPBjserg4qyrg6sgCH6AODs4KbpIif9LCi1iPTjux7t7j7t3gFArMdXsmABUzTIS0YiYzqyKvld0ox+9CGFMYqYeSy6m0HZ83cPD17swz2p/7s/Rp2RNBnhE4jmmGxbxBvHMpqVz3icOsIKkEJ8Tjxt0QeJHrssuv3HOOyzwzICRSswTB4jFfAvLLcwKhko8TRxUVI3yhbTLCuctzmqpwhr35C/0Z7WVJNdpjiCKJcQQhwgZFRRRgoUwrRopJhK0H2njH3b8cXLJ5CqCkWMBZaiQHD/4H/zu1sxNTbpJ/gjQ+WLbH6OAbxeoV237+9i26yeA9xm40pr+cg2Y/SS92tSCR8DANnBx3dTkPeByBxh60iVDciQvTSGXA97P6JsywOAt0LPm9tbYx+kDkKKulm+Ag0MglKfs9Tbv7mrt7d8zjf5+AKwQcr5IxI7XAAAABmJLR0QAIgAmANmBjZc5AAAACXBIWXMAAA3XAAAN1wFCKJt4AAAAB3RJTUUH6AkOCykGenzzMgAAASdJREFUKM+10s0rrVEUBvDfe7pF3QzuSJJ8nLq5A/kjFDNlhP+Akk4ZmYrBKUMDGZgZyceEESUDxUAG6A44V+mIEUryEa/JUm9vR5ncXbu113qetdfaz9rKe8XD8l5xyn9aP7CKw/CTDJZm/DSDF/AW5zRj1YglsI4R9ONv7CpmcY1jNKMtGnnELhbRhQs0RP4B6lHBDo4K6EQxLviNaTxhEOOBD0TBRpTQEdwWtOIX2vEnirXjJyYKOcmesYQTnGIZ90HuxiYWsFFD7iTnl7GdL5BmbJqLnaIXQ+iL2F3YYfTgNsN//xzyeehYxVmAFdQFuRL4WnyIueC+Yh8rmAlZS3jAFS5rPS3J2CQXG8NkzOYf5jN5DdHQV3J9a43iBi/YQtN3kj4AFxBKjOA6Ir4AAAAASUVORK5CYII="}),attributes:{preview:{type:"boolean",default:!1},images:{type:"array",default:[]},blockId:{type:"string",default:""},error:{type:"string",default:""},spinner:{type:"boolean",default:!1}},edit:function(e){m((()=>{e.setAttributes({blockId:e.clientId})}));const r=p((e=>e("core").getSite()?.iufi_allow_multiple_files),[]),s=p((e=>e("core").getSite()?.iufi_file_types),[]);function l(r){e.setAttributes({error:(0,t.__)("An error occurred:","image-upload-for-imgur")+" "+r})}m((()=>{if(e.attributes.images.length>1)for(let t in e.attributes.images)if(t<e.attributes.images.length-1){const r=wp.blocks.createBlock("core/embed",{url:e.attributes.images[t]});A("core/block-editor").insertBlocks(r)}}));const c=(0,a.useBlockProps)({className:"image-upload-for-imgur-wrapper"});return(0,i.createElement)("div",{...c},e.attributes.spinner&&(0,i.createElement)(o.Spinner,null),!e.attributes.spinner&&0===e.attributes.images.length&&(0,i.createElement)(o.FormFileUpload,{accept:s.map((e=>e)).join(","),multiple:1===r,onChange:t=>{(async t=>{try{const r=new FormData;r.append("post",wp.data.select("core/editor").getCurrentPostId());let i=0;Array.from(t).map((e=>{i++,r.append("file"+i,e)})),e.setAttributes({spinner:!0}),await n()({path:"image-upload-for-imgur/v1/files",method:"POST",body:r}).then((t=>{e.setAttributes({spinner:!1}),t.error?l(t.error):e.setAttributes({images:t,error:""})}))}catch(e){l(e.message)}})(t.target.files)},className:"button"},(0,t.__)("Choose files to upload","image-upload-for-imgur")),!e.attributes.spinner&&e.attributes.error.length>0&&(0,i.createElement)("p",{className:"error"},e.attributes.error),!e.attributes.spinner&&e.attributes.images.length>0&&(0,i.createElement)(u,{clientId:e.attributes.blockId,blockType:"core/embed",attributes:{url:e.attributes.images[e.attributes.images.length-1]}}))},save:function(e){return null}})})();