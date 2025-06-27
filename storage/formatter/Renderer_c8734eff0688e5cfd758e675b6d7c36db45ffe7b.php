<?php

/**
* @package   s9e\TextFormatter
* @copyright Copyright (c) 2010-2023 The s9e authors
* @license   http://www.opensource.org/licenses/mit-license.php The MIT License
*/
class Renderer_c8734eff0688e5cfd758e675b6d7c36db45ffe7b extends \s9e\TextFormatter\Renderers\PHP
{
	protected $params=['DISCUSSION_URL'=>'https://beta.chuyenbienhoa.com/d/','L_WROTE'=>'','PROFILE_URL'=>'https://beta.chuyenbienhoa.com/u/','TAG_URL'=>'https://beta.chuyenbienhoa.com/t/'];
	protected function renderNode(\DOMNode $node)
	{
		switch($node->nodeName){case'B':$this->out.='<b>';$this->at($node);$this->out.='</b>';break;case'C':$this->out.='<code>';$this->at($node);$this->out.='</code>';break;case'CENTER':$this->out.='<div style="text-align:center">';$this->at($node);$this->out.='</div>';break;case'CODE':$this->out.='<pre><code';if($node->hasAttribute('lang'))$this->out.=' class="language-'.htmlspecialchars($node->getAttribute('lang'),2).'"';$this->out.='>';$this->at($node);$this->out.='</code><script async="" crossorigin="anonymous" data-hljs-style="github" integrity="sha384-E9ssooeJ4kPel3JD7st0BgS50OLWFEdg4ZOp8lYPy52ctQazOIV37TCvzV8l4cYG" src="https://cdn.jsdelivr.net/gh/s9e/hljs-loader@1.0.34/loader.min.js"></script><script>
                    if(window.hljsLoader && !document.currentScript.parentNode.hasAttribute(\'data-s9e-livepreview-onupdate\')) {
                        window.hljsLoader.highlightBlocks(document.currentScript.parentNode);
                    }
                </script></pre>';break;case'COLOR':$this->out.='<span style="color:'.htmlspecialchars($node->getAttribute('color'),2).'">';$this->at($node);$this->out.='</span>';break;case'DEL':$this->out.='<del>';$this->at($node);$this->out.='</del>';break;case'EM':$this->out.='<em>';$this->at($node);$this->out.='</em>';break;case'EMAIL':$this->out.='<a href="mailto:'.htmlspecialchars($node->getAttribute('email'),2).'">';$this->at($node);$this->out.='</a>';break;case'ESC':$this->at($node);break;case'GROUPMENTION':$this->out.='<span';if($node->getAttribute('deleted')!=1)if($this->xpath->evaluate('string(@color)!=\'\'',$node))$this->out.=' class="GroupMention GroupMention--colored" style="--color:'.htmlspecialchars($node->getAttribute('color'),2).';"';else$this->out.=' class="GroupMention"';else$this->out.=' class="GroupMention GroupMention--deleted"';$this->out.='><span class="GroupMention-name">@'.htmlspecialchars($node->getAttribute('groupname'),0).'</span>';if($this->xpath->evaluate('string(@icon)!=\'\'',$node))$this->out.='<i class="icon '.htmlspecialchars($node->getAttribute('icon'),2).'"></i>';$this->out.='</span>';break;case'H1':$this->out.='<h1>';$this->at($node);$this->out.='</h1>';break;case'H2':$this->out.='<h2>';$this->at($node);$this->out.='</h2>';break;case'H3':$this->out.='<h3>';$this->at($node);$this->out.='</h3>';break;case'H4':$this->out.='<h4>';$this->at($node);$this->out.='</h4>';break;case'H5':$this->out.='<h5>';$this->at($node);$this->out.='</h5>';break;case'H6':$this->out.='<h6>';$this->at($node);$this->out.='</h6>';break;case'HR':$this->out.='<hr>';break;case'I':$this->out.='<i>';$this->at($node);$this->out.='</i>';break;case'IMG':$this->out.='<img src="'.htmlspecialchars($node->getAttribute('src'),2).'" title="'.htmlspecialchars($node->getAttribute('title'),2).'" alt="'.htmlspecialchars($node->getAttribute('alt'),2).'"';if($node->hasAttribute('height'))$this->out.=' height="'.htmlspecialchars($node->getAttribute('height'),2).'"';if($node->hasAttribute('width'))$this->out.=' width="'.htmlspecialchars($node->getAttribute('width'),2).'"';$this->out.='>';break;case'ISPOILER':$this->out.='<span class="spoiler" onclick="removeAttribute(\'class\')">';$this->at($node);$this->out.='</span>';break;case'LI':$this->out.='<li>';$this->at($node);$this->out.='</li>';break;case'LIST':if(!$node->hasAttribute('type')){$this->out.='<ul>';$this->at($node);$this->out.='</ul>';}elseif(str_starts_with($node->getAttribute('type'),'decimal')||str_starts_with($node->getAttribute('type'),'lower')||str_starts_with($node->getAttribute('type'),'upper')){$this->out.='<ol style="list-style-type:'.htmlspecialchars($node->getAttribute('type'),2).'"';if($node->hasAttribute('start'))$this->out.=' start="'.htmlspecialchars($node->getAttribute('start'),2).'"';$this->out.='>';$this->at($node);$this->out.='</ol>';}else{$this->out.='<ul style="list-style-type:'.htmlspecialchars($node->getAttribute('type'),2).'">';$this->at($node);$this->out.='</ul>';}break;case'POSTMENTION':if($node->getAttribute('deleted')!=1)$this->out.='<a href="'.htmlspecialchars($this->params['DISCUSSION_URL'].$node->getAttribute('discussionid'),2).'/'.htmlspecialchars($node->getAttribute('number'),2).'" class="PostMention" data-id="'.htmlspecialchars($node->getAttribute('id'),2).'">'.htmlspecialchars($node->getAttribute('displayname'),0).'</a>';else$this->out.='<span class="PostMention PostMention--deleted" data-id="'.htmlspecialchars($node->getAttribute('id'),2).'">'.htmlspecialchars($node->getAttribute('displayname'),0).'</span>';break;case'QUOTE':$this->out.='<blockquote';if(!$node->hasAttribute('author'))$this->out.=' class="uncited"';$this->out.='><div>';if($node->hasAttribute('author'))$this->out.='<cite>'.htmlspecialchars($node->getAttribute('author'),0).' '.htmlspecialchars($this->params['L_WROTE'],0).'</cite>';$this->at($node);$this->out.='</div></blockquote>';break;case'S':$this->out.='<s>';$this->at($node);$this->out.='</s>';break;case'SIZE':$this->out.='<span style="font-size:'.htmlspecialchars($node->getAttribute('size'),2).'px">';$this->at($node);$this->out.='</span>';break;case'SPOILER':$this->out.='<details class="spoiler">';$this->at($node);$this->out.='</details>';break;case'STRONG':$this->out.='<strong>';$this->at($node);$this->out.='</strong>';break;case'SUB':$this->out.='<sub>';$this->at($node);$this->out.='</sub>';break;case'SUP':$this->out.='<sup>';$this->at($node);$this->out.='</sup>';break;case'TAGMENTION':if($node->getAttribute('deleted')!=1){$this->out.='<a href="'.htmlspecialchars($this->params['TAG_URL'].$node->getAttribute('slug'),2).'" data-id="'.htmlspecialchars($node->getAttribute('id'),2).'" class="TagMention';if($node->getAttribute('color')!=='')$this->out.=' TagMention--colored';$this->out.='" style="';if($node->getAttribute('color')!=='')$this->out.='--color:'.htmlspecialchars($node->getAttribute('color'),2);$this->out.='"><span class="TagMention-text">';if($node->getAttribute('icon')!=='')$this->out.='<i class="icon '.htmlspecialchars($node->getAttribute('icon'),2).'"></i>';$this->out.=htmlspecialchars($node->getAttribute('tagname'),0).'</span></a>';}else$this->out.='<span class="TagMention TagMention--deleted" data-id="'.htmlspecialchars($node->getAttribute('id'),2).'"><span class="TagMention-text">'.htmlspecialchars($node->getAttribute('tagname'),0).'</span></span>';break;case'U':$this->out.='<u>';$this->at($node);$this->out.='</u>';break;case'UPL-FILE':$this->out.='<div class="ButtonGroup" data-fof-upload-download-uuid="'.htmlspecialchars($node->getAttribute('uuid'),2).'"><div class="Button hasIcon Button--icon Button--primary"><i class="fas fa-download"></i></div><div class="Button">'.htmlspecialchars($node->getAttribute('content'),0).'</div><div class="Button">'.htmlspecialchars($node->getAttribute('size'),0).'</div></div>';break;case'UPL-IMAGE':$this->out.='<div class="fof-download row" data-fof-upload-download-uuid="'.htmlspecialchars($node->getAttribute('uuid'),2).'"><div class="card"><div class="wrapper" style="background:url('.htmlspecialchars($node->getAttribute('url'),2).') center / cover no-repeat"><div class="header"><ul class="menu-content"><li><div class="far fa-hdd"><span>'.htmlspecialchars($node->getAttribute('size'),0).'</span></div></li></ul></div><div class="data"><div class="content"><h4 class="title">'.htmlspecialchars($node->getAttribute('content'),0).'</h4><div class="Button Button--primary Button-icon Button--block"><i class="fas fa-download"></i></div></div></div></div></div></div>';break;case'UPL-IMAGE-PREVIEW':$this->out.='<img class="FoFUpload--Upl-Image-Preview" src="'.htmlspecialchars($node->getAttribute('url'),2).'" title="'.htmlspecialchars($node->getAttribute('title'),2).'" alt="'.htmlspecialchars($node->getAttribute('alt'),2).'" data-id="'.htmlspecialchars($node->getAttribute('uuid'),2).'" loading="lazy">';break;case'UPL-TEXT-PREVIEW':$this->out.='<figure class="FofUpload-TextPreview" data-loading="false" data-expanded="false" data-hassnippet="'.htmlspecialchars($node->getAttribute('has_snippet'),2).'"><figcaption class="FofUpload-TextPreviewTitle"><i aria-hidden="true" class="icon far fa-file"></i> '.htmlspecialchars($node->getAttribute('content'),0).'</figcaption><div class="FofUpload-TextPreviewSnippet"><pre><code data-preview-text="&lt;bản xem trước sẽ xuất hiện ở đây sau khi đăng&gt;" data-nosnippet-text="&lt;không có sẵn đoạn mã xem trước&gt;">'.htmlspecialchars($node->getAttribute('snippet'),0).'</code></pre></div><div class="FofUpload-TextPreviewFull"></div><button type="button" class="Button hasIcon FofUpload-TextPreviewToggle"><i aria-hidden="true" class="icon fas fa-chevron-down Button-icon FofUpload-TextPreviewExpandIcon"></i><span class="Button-label FofUpload-TextPreviewExpand">
            Mở rộng bản xem trước        </span><i aria-hidden="true" class="icon fas fa-chevron-up Button-icon FofUpload-TextPreviewCollapseIcon"></i><span class="Button-label FofUpload-TextPreviewCollapse">
            Thu gọn bản xem trước        </span><div data-size="small" class="FofUpload-TextPreviewToggleLoading LoadingIndicator-container LoadingIndicator-container--inline LoadingIndicator-container--small"><div aria-hidden="true" class="LoadingIndicator"></div></div></button><div class="FofUpload-TextPreviewError"><p><i aria-hidden="true" class="icon fas fa-exclamation-circle"></i>
            Lỗi khi xem trước tệp. Nó có thể đã bị xóa hoặc ID tệp được cung cấp không hợp lệ.
        </p></div><script>
        {
            const figure = document.currentScript.parentElement;

            const previewEl = figure.querySelector(\'.FofUpload-TextPreviewFull\');
            const snippetEl = figure.querySelector(\'.FofUpload-TextPreviewSnippet\');
            const loadingEl = figure.querySelector(\'.FofUpload-TextPreviewLoading\');
            const toggleBtn = figure.querySelector(\'.FofUpload-TextPreviewToggle\');

            const snippetText = \'\';

            const testUrl = new URL(location.origin);
            const url = new URL(\''.$node->getAttribute('url').'\');

            if (testUrl.origin !== url.origin) {
              // Prevent cross-origin requests
              handleError(new Error(\'Attempted to fetch a cross-origin file in text preview.\'));
            }

            function createCodeHtml(text) {
                const codeEl = document.createElement(\'code\');
                codeEl.innerText = text;

                return `<pre>${codeEl.outerHTML}`;
            }

            function handleError(e) {
                figure.setAttribute(\'data-error\', \'true\');

                console.group(\'[FoF Upload] Failed to preview text file.\');
                console.error(\'Failed to load text file: \' + url);
                console.log(e);
                console.groupEnd();
            }

            let fileContent = null;

            // Only allow toggling preview if showing a snippet
            if ('.$node->getAttribute('has_snippet').' && testUrl.origin === url.origin) {
                toggleBtn.addEventListener(\'click\', () => {
                    if (fileContent !== null) {
                        const expanded = figure.getAttribute(\'data-expanded\') === \'true\';
                        figure.setAttribute(\'data-expanded\', !expanded);
                        return;
                    }

                    figure.setAttribute(\'data-loading\', \'true\');

                    fetch(url)
                        .then(response => {
                            if (!response.ok) {
                                figure.setAttribute(\'data-loading\', \'false\');
                                throw response;
                            }

                            return response.text();
                        })
                        .then(text => {
                            fileContent = text;
                            previewEl.innerHTML = createCodeHtml(text);

                            figure.setAttribute(\'data-loading\', \'false\');
                            const expanded = figure.getAttribute(\'data-expanded\') === \'true\';
                            figure.setAttribute(\'data-expanded\', !expanded);
                        })
                        .catch(handleError);
                });
            }
        }
    </script></figure>';break;case'URL':$this->out.='<a href="'.htmlspecialchars($node->getAttribute('url'),2).'"';if($node->hasAttribute('rel'))$this->out.=' rel="'.htmlspecialchars($node->getAttribute('rel'),2).'"';if($node->hasAttribute('target'))$this->out.=' target="'.htmlspecialchars($node->getAttribute('target'),2).'"';if($node->hasAttribute('title'))$this->out.=' title="'.htmlspecialchars($node->getAttribute('title'),2).'"';$this->out.='>';$this->at($node);$this->out.='</a>';break;case'USERMENTION':if($node->getAttribute('deleted')!=1)$this->out.='<a href="'.htmlspecialchars($this->params['PROFILE_URL'].$node->getAttribute('slug'),2).'" class="UserMention">@'.htmlspecialchars($node->getAttribute('displayname'),0).'</a>';else$this->out.='<span class="UserMention UserMention--deleted">@'.htmlspecialchars($node->getAttribute('displayname'),0).'</span>';break;case'br':$this->out.='<br>';break;case'e':case'i':case's':break;case'p':$this->out.='<p>';$this->at($node);$this->out.='</p>';break;default:$this->at($node);}
	}
	/** {@inheritdoc} */
	public $enableQuickRenderer=true;
	/** {@inheritdoc} */
	protected $static=['/B'=>'</b>','/C'=>'</code>','/CENTER'=>'</div>','/CODE'=>'</code><script async="" crossorigin="anonymous" data-hljs-style="github" integrity="sha384-E9ssooeJ4kPel3JD7st0BgS50OLWFEdg4ZOp8lYPy52ctQazOIV37TCvzV8l4cYG" src="https://cdn.jsdelivr.net/gh/s9e/hljs-loader@1.0.34/loader.min.js"></script><script>
                    if(window.hljsLoader && !document.currentScript.parentNode.hasAttribute(\'data-s9e-livepreview-onupdate\')) {
                        window.hljsLoader.highlightBlocks(document.currentScript.parentNode);
                    }
                </script></pre>','/COLOR'=>'</span>','/DEL'=>'</del>','/EM'=>'</em>','/EMAIL'=>'</a>','/ESC'=>'','/H1'=>'</h1>','/H2'=>'</h2>','/H3'=>'</h3>','/H4'=>'</h4>','/H5'=>'</h5>','/H6'=>'</h6>','/I'=>'</i>','/ISPOILER'=>'</span>','/LI'=>'</li>','/QUOTE'=>'</div></blockquote>','/S'=>'</s>','/SIZE'=>'</span>','/SPOILER'=>'</details>','/STRONG'=>'</strong>','/SUB'=>'</sub>','/SUP'=>'</sup>','/U'=>'</u>','/URL'=>'</a>','B'=>'<b>','C'=>'<code>','CENTER'=>'<div style="text-align:center">','DEL'=>'<del>','EM'=>'<em>','ESC'=>'','H1'=>'<h1>','H2'=>'<h2>','H3'=>'<h3>','H4'=>'<h4>','H5'=>'<h5>','H6'=>'<h6>','HR'=>'<hr>','I'=>'<i>','ISPOILER'=>'<span class="spoiler" onclick="removeAttribute(\'class\')">','LI'=>'<li>','S'=>'<s>','SPOILER'=>'<details class="spoiler">','STRONG'=>'<strong>','SUB'=>'<sub>','SUP'=>'<sup>','U'=>'<u>'];
	/** {@inheritdoc} */
	protected $dynamic=['COLOR'=>['(^[^ ]+(?> (?!color=)[^=]+="[^"]*")*(?> color="([^"]*)")?.*)s','<span style="color:$1">'],'EMAIL'=>['(^[^ ]+(?> (?!email=)[^=]+="[^"]*")*(?> email="([^"]*)")?.*)s','<a href="mailto:$1">'],'IMG'=>['(^[^ ]+(?> (?!(?:alt|height|src|title|width)=)[^=]+="[^"]*")*(?> alt="([^"]*)")?(?> (?!(?:height|src|title|width)=)[^=]+="[^"]*")*( height="[^"]*")?(?> (?!(?:src|title|width)=)[^=]+="[^"]*")*(?> src="([^"]*)")?(?> (?!(?:title|width)=)[^=]+="[^"]*")*(?> title="([^"]*)")?(?> (?!width=)[^=]+="[^"]*")*( width="[^"]*")?.*)s','<img src="$3" title="$4" alt="$1"$2$5>'],'SIZE'=>['(^[^ ]+(?> (?!size=)[^=]+="[^"]*")*(?> size="([^"]*)")?.*)s','<span style="font-size:$1px">'],'UPL-IMAGE-PREVIEW'=>['(^[^ ]+(?> (?!(?:alt|title|u(?:rl|uid))=)[^=]+="[^"]*")*(?> alt="([^"]*)")?(?> (?!(?:title|u(?:rl|uid))=)[^=]+="[^"]*")*(?> title="([^"]*)")?(?> (?!u(?:rl|uid)=)[^=]+="[^"]*")*(?> url="([^"]*)")?(?> (?!uuid=)[^=]+="[^"]*")*(?> uuid="([^"]*)")?.*)s','<img class="FoFUpload--Upl-Image-Preview" src="$3" title="$2" alt="$1" data-id="$4" loading="lazy">'],'URL'=>['(^[^ ]+(?> (?!(?:rel|t(?:arget|itle)|url)=)[^=]+="[^"]*")*( rel="[^"]*")?(?> (?!(?:t(?:arget|itle)|url)=)[^=]+="[^"]*")*( target="[^"]*")?(?> (?!(?:title|url)=)[^=]+="[^"]*")*( title="[^"]*")?(?> (?!url=)[^=]+="[^"]*")*(?> url="([^"]*)")?.*)s','<a href="$4"$1$2$3>']];
	/** {@inheritdoc} */
	protected $quickRegexp='(<(?:(?!/)((?:HR|IMG|POSTMENTION|TAGMENTION|U(?:PL-(?:FILE|IMAGE(?:-PREVIEW)?|TEXT-PREVIEW)|SERMENTION)))(?: [^>]*)?>.*?</\\1|(/?(?!br/|p>)[^ />]+)[^>]*?(/)?)>)s';
	/** {@inheritdoc} */
	protected $quickRenderingTest='((?<=<)(?:[!?]|GROUPMENTION[ />]))';
	/** {@inheritdoc} */
	protected function renderQuickTemplate($id, $xml)
	{
		$attributes=$this->matchAttributes($xml);
		$html='';switch($id){case'/LIST':$attributes=array_pop($this->attributes);if(!isset($attributes['type']))$html.='</ul>';elseif(str_starts_with($attributes['type']??'','decimal')||str_starts_with($attributes['type']??'','lower')||str_starts_with($attributes['type']??'','upper'))$html.='</ol>';else$html.='</ul>';break;case'CODE':$html.='<pre><code';if(isset($attributes['lang']))$html.=' class="language-'.$attributes['lang'].'"';$html.='>';break;case'LIST':$attributes+=['type'=>null];if(!isset($attributes['type']))$html.='<ul>';elseif(str_starts_with($attributes['type']??'','decimal')||str_starts_with($attributes['type']??'','lower')||str_starts_with($attributes['type']??'','upper')){$html.='<ol style="list-style-type:'.$attributes['type'].'"';if(isset($attributes['start']))$html.=' start="'.$attributes['start'].'"';$html.='>';}else$html.='<ul style="list-style-type:'.$attributes['type'].'">';$this->attributes[]=$attributes;break;case'POSTMENTION':$attributes+=['deleted'=>null,'discussionid'=>null,'number'=>null,'id'=>null,'displayname'=>null];if(htmlspecialchars_decode($attributes['deleted']??'')!=1)$html.='<a href="'.htmlspecialchars($this->params['DISCUSSION_URL'].htmlspecialchars_decode($attributes['discussionid']??''),2).'/'.$attributes['number'].'" class="PostMention" data-id="'.$attributes['id'].'">'.str_replace('&quot;','"',$attributes['displayname']??'').'</a>';else$html.='<span class="PostMention PostMention--deleted" data-id="'.$attributes['id'].'">'.str_replace('&quot;','"',$attributes['displayname']??'').'</span>';break;case'QUOTE':$html.='<blockquote';if(!isset($attributes['author']))$html.=' class="uncited"';$html.='><div>';if(isset($attributes['author']))$html.='<cite>'.str_replace('&quot;','"',$attributes['author']??'').' '.htmlspecialchars($this->params['L_WROTE'],0).'</cite>';break;case'TAGMENTION':$attributes+=['deleted'=>null,'slug'=>null,'id'=>null,'color'=>null,'icon'=>null,'tagname'=>null];if(htmlspecialchars_decode($attributes['deleted']??'')!=1){$html.='<a href="'.htmlspecialchars($this->params['TAG_URL'].htmlspecialchars_decode($attributes['slug']??''),2).'" data-id="'.$attributes['id'].'" class="TagMention';if(htmlspecialchars_decode($attributes['color']??'')!=='')$html.=' TagMention--colored';$html.='" style="';if(htmlspecialchars_decode($attributes['color']??'')!=='')$html.='--color:'.$attributes['color'];$html.='"><span class="TagMention-text">';if(htmlspecialchars_decode($attributes['icon']??'')!=='')$html.='<i class="icon '.$attributes['icon'].'"></i>';$html.=str_replace('&quot;','"',$attributes['tagname']??'').'</span></a>';}else$html.='<span class="TagMention TagMention--deleted" data-id="'.$attributes['id'].'"><span class="TagMention-text">'.str_replace('&quot;','"',$attributes['tagname']??'').'</span></span>';break;case'UPL-FILE':$attributes+=['uuid'=>null,'content'=>null,'size'=>null];$html.='<div class="ButtonGroup" data-fof-upload-download-uuid="'.$attributes['uuid'].'"><div class="Button hasIcon Button--icon Button--primary"><i class="fas fa-download"></i></div><div class="Button">'.str_replace('&quot;','"',$attributes['content']??'').'</div><div class="Button">'.str_replace('&quot;','"',$attributes['size']??'').'</div></div>';break;case'UPL-IMAGE':$attributes+=['uuid'=>null,'url'=>null,'size'=>null,'content'=>null];$html.='<div class="fof-download row" data-fof-upload-download-uuid="'.$attributes['uuid'].'"><div class="card"><div class="wrapper" style="background:url('.$attributes['url'].') center / cover no-repeat"><div class="header"><ul class="menu-content"><li><div class="far fa-hdd"><span>'.str_replace('&quot;','"',$attributes['size']??'').'</span></div></li></ul></div><div class="data"><div class="content"><h4 class="title">'.str_replace('&quot;','"',$attributes['content']??'').'</h4><div class="Button Button--primary Button-icon Button--block"><i class="fas fa-download"></i></div></div></div></div></div></div>';break;case'UPL-TEXT-PREVIEW':$attributes+=['has_snippet'=>null,'content'=>null,'snippet'=>null,'url'=>null];$html.='<figure class="FofUpload-TextPreview" data-loading="false" data-expanded="false" data-hassnippet="'.$attributes['has_snippet'].'"><figcaption class="FofUpload-TextPreviewTitle"><i aria-hidden="true" class="icon far fa-file"></i> '.str_replace('&quot;','"',$attributes['content']??'').'</figcaption><div class="FofUpload-TextPreviewSnippet"><pre><code data-preview-text="&lt;bản xem trước sẽ xuất hiện ở đây sau khi đăng&gt;" data-nosnippet-text="&lt;không có sẵn đoạn mã xem trước&gt;">'.str_replace('&quot;','"',$attributes['snippet']??'').'</code></pre></div><div class="FofUpload-TextPreviewFull"></div><button type="button" class="Button hasIcon FofUpload-TextPreviewToggle"><i aria-hidden="true" class="icon fas fa-chevron-down Button-icon FofUpload-TextPreviewExpandIcon"></i><span class="Button-label FofUpload-TextPreviewExpand">
            Mở rộng bản xem trước        </span><i aria-hidden="true" class="icon fas fa-chevron-up Button-icon FofUpload-TextPreviewCollapseIcon"></i><span class="Button-label FofUpload-TextPreviewCollapse">
            Thu gọn bản xem trước        </span><div data-size="small" class="FofUpload-TextPreviewToggleLoading LoadingIndicator-container LoadingIndicator-container--inline LoadingIndicator-container--small"><div aria-hidden="true" class="LoadingIndicator"></div></div></button><div class="FofUpload-TextPreviewError"><p><i aria-hidden="true" class="icon fas fa-exclamation-circle"></i>
            Lỗi khi xem trước tệp. Nó có thể đã bị xóa hoặc ID tệp được cung cấp không hợp lệ.
        </p></div><script>
        {
            const figure = document.currentScript.parentElement;

            const previewEl = figure.querySelector(\'.FofUpload-TextPreviewFull\');
            const snippetEl = figure.querySelector(\'.FofUpload-TextPreviewSnippet\');
            const loadingEl = figure.querySelector(\'.FofUpload-TextPreviewLoading\');
            const toggleBtn = figure.querySelector(\'.FofUpload-TextPreviewToggle\');

            const snippetText = \'\';

            const testUrl = new URL(location.origin);
            const url = new URL(\''.htmlspecialchars_decode($attributes['url']??'').'\');

            if (testUrl.origin !== url.origin) {
              // Prevent cross-origin requests
              handleError(new Error(\'Attempted to fetch a cross-origin file in text preview.\'));
            }

            function createCodeHtml(text) {
                const codeEl = document.createElement(\'code\');
                codeEl.innerText = text;

                return `<pre>${codeEl.outerHTML}`;
            }

            function handleError(e) {
                figure.setAttribute(\'data-error\', \'true\');

                console.group(\'[FoF Upload] Failed to preview text file.\');
                console.error(\'Failed to load text file: \' + url);
                console.log(e);
                console.groupEnd();
            }

            let fileContent = null;

            // Only allow toggling preview if showing a snippet
            if ('.htmlspecialchars_decode($attributes['has_snippet']??'').' && testUrl.origin === url.origin) {
                toggleBtn.addEventListener(\'click\', () => {
                    if (fileContent !== null) {
                        const expanded = figure.getAttribute(\'data-expanded\') === \'true\';
                        figure.setAttribute(\'data-expanded\', !expanded);
                        return;
                    }

                    figure.setAttribute(\'data-loading\', \'true\');

                    fetch(url)
                        .then(response => {
                            if (!response.ok) {
                                figure.setAttribute(\'data-loading\', \'false\');
                                throw response;
                            }

                            return response.text();
                        })
                        .then(text => {
                            fileContent = text;
                            previewEl.innerHTML = createCodeHtml(text);

                            figure.setAttribute(\'data-loading\', \'false\');
                            const expanded = figure.getAttribute(\'data-expanded\') === \'true\';
                            figure.setAttribute(\'data-expanded\', !expanded);
                        })
                        .catch(handleError);
                });
            }
        }
    </script></figure>';break;case'USERMENTION':$attributes+=['deleted'=>null,'slug'=>null,'displayname'=>null];if(htmlspecialchars_decode($attributes['deleted']??'')!=1)$html.='<a href="'.htmlspecialchars($this->params['PROFILE_URL'].htmlspecialchars_decode($attributes['slug']??''),2).'" class="UserMention">@'.str_replace('&quot;','"',$attributes['displayname']??'').'</a>';else$html.='<span class="UserMention UserMention--deleted">@'.str_replace('&quot;','"',$attributes['displayname']??'').'</span>';}

		return $html;
	}
}