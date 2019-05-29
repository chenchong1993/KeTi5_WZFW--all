//>>built
define("dojox/data/QueryReadStore",["dojo","dojox","dojo/data/util/sorter","dojo/string"],function(e,p){return e.declare("dojox.data.QueryReadStore",null,{url:"",requestMethod:"get",_className:"dojox.data.QueryReadStore",_items:[],_lastServerQuery:null,_numRows:-1,lastRequestHash:null,doClientPaging:!1,doClientSorting:!1,_itemsByIdentity:null,_identifier:null,_features:{"dojo.data.api.Read":!0,"dojo.data.api.Identity":!0},_labelAttr:"label",constructor:function(a){e.mixin(this,a)},getValue:function(a,
c,f){this._assertIsItem(a);if(!e.isString(c))throw Error(this._className+".getValue(): Invalid attribute, string expected!");return!this.hasAttribute(a,c)&&f?f:a.i[c]},getValues:function(a,c){this._assertIsItem(a);var f=[];this.hasAttribute(a,c)&&f.push(a.i[c]);return f},getAttributes:function(a){this._assertIsItem(a);var c=[],f;for(f in a.i)c.push(f);return c},hasAttribute:function(a,c){return this.isItem(a)&&"undefined"!=typeof a.i[c]},containsValue:function(a,c,f){a=this.getValues(a,c);c=a.length;
for(var b=0;b<c;b++)if(a[b]==f)return!0;return!1},isItem:function(a){return a?"undefined"!=typeof a.r&&a.r==this:!1},isItemLoaded:function(a){return this.isItem(a)},loadItem:function(a){this.isItemLoaded(a.item)},fetch:function(a){a=a||{};a.store||(a.store=this);var c=this;this._fetchItems(a,function(a,b,d){var g=b.abort||null,h=!1,k=b.start?b.start:0;!1==c.doClientPaging&&(k=0);var m=b.count?k+b.count:a.length;b.abort=function(){h=!0;g&&g.call(b)};var l=b.scope||e.global;b.store||(b.store=c);b.onBegin&&
b.onBegin.call(l,d,b);b.sort&&c.doClientSorting&&a.sort(e.data.util.sorter.createSortFunction(b.sort,c));if(b.onItem)for(d=k;d<a.length&&d<m;++d){var n=a[d];h||b.onItem.call(l,n,b)}b.onComplete&&!h&&(d=null,b.onItem||(d=a.slice(k,m)),b.onComplete.call(l,d,b))},function(a,b){b.onError&&b.onError.call(b.scope||e.global,a,b)});return a},getFeatures:function(){return this._features},close:function(a){},getLabel:function(a){if(this._labelAttr&&this.isItem(a))return this.getValue(a,this._labelAttr)},getLabelAttributes:function(a){return this._labelAttr?
[this._labelAttr]:null},_xhrFetchHandler:function(a,c,f,b){a=this._filterResponse(a);a.label&&(this._labelAttr=a.label);b=a.numRows||-1;this._items=[];e.forEach(a.items,function(a){this._items.push({i:a,r:this})},this);a=a.identifier;this._itemsByIdentity={};if(a){this._identifier=a;var d;for(d=0;d<this._items.length;++d){var g=this._items[d].i,h=g[a];if(this._itemsByIdentity[h])throw Error(this._className+":  The json data as specified by: ["+this.url+"] is malformed.  Items within the list have identifier: ["+
a+"].  Value collided: ["+h+"]");this._itemsByIdentity[h]=g}}else{this._identifier=Number;for(d=0;d<this._items.length;++d)this._items[d].n=d}b=this._numRows=-1===b?this._items.length:b;f(this._items,c,b);this._numRows=b},_fetchItems:function(a,c,f){var b=a.serverQuery||a.query||{};this.doClientPaging||(b.start=a.start||0,a.count&&(b.count=a.count));if(!this.doClientSorting&&a.sort){var d=[];e.forEach(a.sort,function(a){a&&a.attribute&&d.push((a.descending?"-":"")+a.attribute)});b.sort=d.join(",")}if(this.doClientPaging&&
null!==this._lastServerQuery&&e.toJson(b)==e.toJson(this._lastServerQuery))this._numRows=-1===this._numRows?this._items.length:this._numRows,c(this._items,a,this._numRows);else{var g=("post"==this.requestMethod.toLowerCase()?e.xhrPost:e.xhrGet)({url:this.url,handleAs:"json-comment-optional",content:b,failOk:!0});a.abort=function(){g.cancel()};g.addCallback(e.hitch(this,function(b){this._xhrFetchHandler(b,a,c,f)}));g.addErrback(function(b){f(b,a)});this.lastRequestHash=(new Date).getTime()+"-"+String(Math.random()).substring(2);
this._lastServerQuery=e.mixin({},b)}},_filterResponse:function(a){return a},_assertIsItem:function(a){if(!this.isItem(a))throw Error(this._className+": Invalid item argument.");},_assertIsAttribute:function(a){if("string"!==typeof a)throw Error(this._className+": Invalid attribute argument ('"+a+"').");},fetchItemByIdentity:function(a){if(this._itemsByIdentity){var c=this._itemsByIdentity[a.identity];if(void 0!==c){a.onItem&&a.onItem.call(a.scope?a.scope:e.global,{i:c,r:this});return}}this._fetchItems({serverQuery:{id:a.identity}},
function(c,b){var d=a.scope?a.scope:e.global;try{var g=null;c&&1==c.length&&(g=c[0]);a.onItem&&a.onItem.call(d,g)}catch(h){a.onError&&a.onError.call(d,h)}},function(c,b){var d=a.scope?a.scope:e.global;a.onError&&a.onError.call(d,c)})},getIdentity:function(a){var c=null;return c=this._identifier===Number?a.n:a.i[this._identifier]},getIdentityAttributes:function(a){return[this._identifier]}})});