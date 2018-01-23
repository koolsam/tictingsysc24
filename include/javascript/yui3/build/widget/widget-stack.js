/*
Copyright (c) 2010, Yahoo! Inc. All rights reserved.
Code licensed under the BSD License:
http://developer.yahoo.com/yui/license.html
version: 3.3.0
build: 3167
*/
YUI.add("widget-stack",function(E){var N=E.Lang,T=E.UA,d=E.Node,F=E.Widget,c="zIndex",P="shim",a="visible",e="boundingBox",W="renderUI",G="bindUI",S="syncUI",Q="offsetWidth",D="offsetHeight",M="parentNode",A="firstChild",X="ownerDocument",H="width",V="height",K="px",O="shimdeferred",f="shimresize",Z="visibleChange",C="widthChange",J="heightChange",b="shimChange",B="zIndexChange",I="contentUpdate",R="stacked";function U(L){this._stackNode=this.get(e);this._stackHandles={};E.after(this._renderUIStack,this,W);E.after(this._syncUIStack,this,S);E.after(this._bindUIStack,this,G);}U.ATTRS={shim:{value:(T.ie==6)},zIndex:{value:0,setter:function(L){return this._setZIndex(L);}}};U.HTML_PARSER={zIndex:function(L){return L.getStyle(c);}};U.SHIM_CLASS_NAME=F.getClassName(P);U.STACKED_CLASS_NAME=F.getClassName(R);U.SHIM_TEMPLATE='<iframe class="'+U.SHIM_CLASS_NAME+'" frameborder="0" title="Widget Stacking Shim" src="javascript:false" tabindex="-1" role="presentation"></iframe>';U.prototype={_syncUIStack:function(){this._uiSetShim(this.get(P));this._uiSetZIndex(this.get(c));},_bindUIStack:function(){this.after(b,this._afterShimChange);this.after(B,this._afterZIndexChange);},_renderUIStack:function(){this._stackNode.addClass(U.STACKED_CLASS_NAME);},_setZIndex:function(L){if(N.isString(L)){L=parseInt(L,10);}if(!N.isNumber(L)){L=0;}return L;},_afterShimChange:function(L){this._uiSetShim(L.newVal);},_afterZIndexChange:function(L){this._uiSetZIndex(L.newVal);},_uiSetZIndex:function(L){this._stackNode.setStyle(c,L);},_uiSetShim:function(L){if(L){if(this.get(a)){this._renderShim();}else{this._renderShimDeferred();}}else{this._destroyShim();}},_renderShimDeferred:function(){this._stackHandles[O]=this._stackHandles[O]||[];var Y=this._stackHandles[O],L=function(g){if(g.newVal){this._renderShim();}};Y.push(this.on(Z,L));},_addShimResizeHandlers:function(){this._stackHandles[f]=this._stackHandles[f]||[];var Y=this.sizeShim,L=this._stackHandles[f];this.sizeShim();L.push(this.after(Z,Y));L.push(this.after(C,Y));L.push(this.after(J,Y));L.push(this.after(I,Y));},_detachStackHandles:function(L){var Y=this._stackHandles[L],g;if(Y&&Y.length>0){while((g=Y.pop())){g.detach();}}},_renderShim:function(){var L=this._shimNode,Y=this._stackNode;if(!L){L=this._shimNode=this._getShimTemplate();Y.insertBefore(L,Y.get(A));if(T.ie==6){this._addShimResizeHandlers();}this._detachStackHandles(O);}},_destroyShim:function(){if(this._shimNode){this._shimNode.get(M).removeChild(this._shimNode);this._shimNode=null;this._detachStackHandles(O);this._detachStackHandles(f);}},sizeShim:function(){var Y=this._shimNode,L=this._stackNode;if(Y&&T.ie===6&&this.get(a)){Y.setStyle(H,L.get(Q)+K);Y.setStyle(V,L.get(D)+K);}},_getShimTemplate:function(){return d.create(U.SHIM_TEMPLATE,this._stackNode.get(X));}};E.WidgetStack=U;},"3.3.0",{requires:["base-build","widget"]});