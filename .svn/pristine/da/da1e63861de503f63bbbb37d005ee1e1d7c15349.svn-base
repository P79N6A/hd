/**
 * url：       要访问统计服务器的url，例如http://analysis.cms.com/analysis/visit
 * channelId： 当前要统计的频道Id，例如统计湘湖网channelId为6
 * itemId：    要统计的新闻，相册或者视频的id
 * title：     新闻，相册或视频的标题
 * editorId:  新闻，相册或视频的编辑
 * terminal：  访问终端'1来自Web,2来自Wap,3来自App'
 * type:      item类型'1新闻，2相册，3视频'
*/
function analysis(url, channelId, itemId, title, editorId, terminal, type)
{
	$.post(url, {
			channel_id : channelId,
			item_id    : itemId,
			title     : title,
			editor_id  : editorId,
			terminal  : terminal,
			type      : type
		}
	);
}