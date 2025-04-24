/*! For license information please see 9567.bundle_eth.js.LICENSE.txt */
(self.webpackChunk=self.webpackChunk||[]).push([[9567],{31:(t,e,n)=>{"use strict";n.d(e,{J:()=>i});var r=n(6752);const i=t=>t??r.s6},157:(t,e,n)=>{const r=n(6886),i=n(9953),o=n(9899),a=n(8820),s=n(6421),c=n(7756),l=n(1332),u=n(5137),h=n(4764),d=n(1427),g=n(4565),f=n(208),p=n(9801);function w(t,e,n){const r=t.size,i=g.getEncodedBits(e,n);let o,a;for(o=0;o<15;o++)a=1==(i>>o&1),o<6?t.set(o,8,a,!0):o<8?t.set(o+1,8,a,!0):t.set(r-15+o,8,a,!0),o<8?t.set(8,r-o-1,a,!0):o<9?t.set(8,15-o-1+1,a,!0):t.set(8,15-o-1,a,!0);t.set(r-8,8,1,!0)}function v(t,e,n,i){let g;if(Array.isArray(t))g=p.fromArray(t);else{if("string"!=typeof t)throw new Error("Invalid data");{let r=e;if(!r){const e=p.rawSplit(t);r=d.getBestVersionForData(e,n)}g=p.fromString(t,r||40)}}const v=d.getBestVersionForData(g,n);if(!v)throw new Error("The amount of data is too big to be stored in a QR Code");if(e){if(e<v)throw new Error("\nThe chosen QR Code version cannot contain this amount of data.\nMinimum version required to store current data is: "+v+".\n")}else e=v;const y=function(t,e,n){const i=new o;n.forEach((function(e){i.put(e.mode.bit,4),i.put(e.getLength(),f.getCharCountIndicator(e.mode,t)),e.write(i)}));const a=8*(r.getSymbolTotalCodewords(t)-u.getTotalCodewordsCount(t,e));for(i.getLengthInBits()+4<=a&&i.put(0,4);i.getLengthInBits()%8!=0;)i.putBit(0);const s=(a-i.getLengthInBits())/8;for(let t=0;t<s;t++)i.put(t%2?17:236,8);return function(t,e,n){const i=r.getSymbolTotalCodewords(e),o=i-u.getTotalCodewordsCount(e,n),a=u.getBlocksCount(e,n),s=a-i%a,c=Math.floor(i/a),l=Math.floor(o/a),d=l+1,g=c-l,f=new h(g);let p=0;const w=new Array(a),v=new Array(a);let y=0;const m=new Uint8Array(t.buffer);for(let t=0;t<a;t++){const e=t<s?l:d;w[t]=m.slice(p,p+e),v[t]=f.encode(w[t]),p+=e,y=Math.max(y,e)}const b=new Uint8Array(i);let x,C,S=0;for(x=0;x<y;x++)for(C=0;C<a;C++)x<w[C].length&&(b[S++]=w[C][x]);for(x=0;x<g;x++)for(C=0;C<a;C++)b[S++]=v[C][x];return b}(i,t,e)}(e,n,g),m=r.getSymbolSize(e),b=new a(m);return function(t,e){const n=t.size,r=c.getPositions(e);for(let e=0;e<r.length;e++){const i=r[e][0],o=r[e][1];for(let e=-1;e<=7;e++)if(!(i+e<=-1||n<=i+e))for(let r=-1;r<=7;r++)o+r<=-1||n<=o+r||(e>=0&&e<=6&&(0===r||6===r)||r>=0&&r<=6&&(0===e||6===e)||e>=2&&e<=4&&r>=2&&r<=4?t.set(i+e,o+r,!0,!0):t.set(i+e,o+r,!1,!0))}}(b,e),function(t){const e=t.size;for(let n=8;n<e-8;n++){const e=n%2==0;t.set(n,6,e,!0),t.set(6,n,e,!0)}}(b),function(t,e){const n=s.getPositions(e);for(let e=0;e<n.length;e++){const r=n[e][0],i=n[e][1];for(let e=-2;e<=2;e++)for(let n=-2;n<=2;n++)-2===e||2===e||-2===n||2===n||0===e&&0===n?t.set(r+e,i+n,!0,!0):t.set(r+e,i+n,!1,!0)}}(b,e),w(b,n,0),e>=7&&function(t,e){const n=t.size,r=d.getEncodedBits(e);let i,o,a;for(let e=0;e<18;e++)i=Math.floor(e/3),o=e%3+n-8-3,a=1==(r>>e&1),t.set(i,o,a,!0),t.set(o,i,a,!0)}(b,e),function(t,e){const n=t.size;let r=-1,i=n-1,o=7,a=0;for(let s=n-1;s>0;s-=2)for(6===s&&s--;;){for(let n=0;n<2;n++)if(!t.isReserved(i,s-n)){let r=!1;a<e.length&&(r=1==(e[a]>>>o&1)),t.set(i,s-n,r),o--,-1===o&&(a++,o=7)}if(i+=r,i<0||n<=i){i-=r,r=-r;break}}}(b,y),isNaN(i)&&(i=l.getBestMask(b,w.bind(null,b,n))),l.applyMask(i,b),w(b,n,i),{modules:b,version:e,errorCorrectionLevel:n,maskPattern:i,segments:g}}e.create=function(t,e){if(void 0===t||""===t)throw new Error("No input text");let n,o,a=i.M;return void 0!==e&&(a=i.from(e.errorCorrectionLevel,i.M),n=d.from(e.version),o=l.from(e.maskPattern),e.toSJISFunc&&r.setToSJISFunction(e.toSJISFunc)),v(t,n,a,o)}},208:(t,e,n)=>{const r=n(1878),i=n(7044);e.NUMERIC={id:"Numeric",bit:1,ccBits:[10,12,14]},e.ALPHANUMERIC={id:"Alphanumeric",bit:2,ccBits:[9,11,13]},e.BYTE={id:"Byte",bit:4,ccBits:[8,16,16]},e.KANJI={id:"Kanji",bit:8,ccBits:[8,10,12]},e.MIXED={bit:-1},e.getCharCountIndicator=function(t,e){if(!t.ccBits)throw new Error("Invalid mode: "+t);if(!r.isValid(e))throw new Error("Invalid version: "+e);return e>=1&&e<10?t.ccBits[0]:e<27?t.ccBits[1]:t.ccBits[2]},e.getBestModeForData=function(t){return i.testNumeric(t)?e.NUMERIC:i.testAlphanumeric(t)?e.ALPHANUMERIC:i.testKanji(t)?e.KANJI:e.BYTE},e.toString=function(t){if(t&&t.id)return t.id;throw new Error("Invalid mode")},e.isValid=function(t){return t&&t.bit&&t.ccBits},e.from=function(t,n){if(e.isValid(t))return t;try{return function(t){if("string"!=typeof t)throw new Error("Param is not a string");switch(t.toLowerCase()){case"numeric":return e.NUMERIC;case"alphanumeric":return e.ALPHANUMERIC;case"kanji":return e.KANJI;case"byte":return e.BYTE;default:throw new Error("Unknown mode: "+t)}}(t)}catch(t){return n}}},310:(t,e,n)=>{"use strict";n(9807)},1332:(t,e)=>{e.Patterns={PATTERN000:0,PATTERN001:1,PATTERN010:2,PATTERN011:3,PATTERN100:4,PATTERN101:5,PATTERN110:6,PATTERN111:7};function n(t,n,r){switch(t){case e.Patterns.PATTERN000:return(n+r)%2==0;case e.Patterns.PATTERN001:return n%2==0;case e.Patterns.PATTERN010:return r%3==0;case e.Patterns.PATTERN011:return(n+r)%3==0;case e.Patterns.PATTERN100:return(Math.floor(n/2)+Math.floor(r/3))%2==0;case e.Patterns.PATTERN101:return n*r%2+n*r%3==0;case e.Patterns.PATTERN110:return(n*r%2+n*r%3)%2==0;case e.Patterns.PATTERN111:return(n*r%3+(n+r)%2)%2==0;default:throw new Error("bad maskPattern:"+t)}}e.isValid=function(t){return null!=t&&""!==t&&!isNaN(t)&&t>=0&&t<=7},e.from=function(t){return e.isValid(t)?parseInt(t,10):void 0},e.getPenaltyN1=function(t){const e=t.size;let n=0,r=0,i=0,o=null,a=null;for(let s=0;s<e;s++){r=i=0,o=a=null;for(let c=0;c<e;c++){let e=t.get(s,c);e===o?r++:(r>=5&&(n+=r-5+3),o=e,r=1),e=t.get(c,s),e===a?i++:(i>=5&&(n+=i-5+3),a=e,i=1)}r>=5&&(n+=r-5+3),i>=5&&(n+=i-5+3)}return n},e.getPenaltyN2=function(t){const e=t.size;let n=0;for(let r=0;r<e-1;r++)for(let i=0;i<e-1;i++){const e=t.get(r,i)+t.get(r,i+1)+t.get(r+1,i)+t.get(r+1,i+1);4!==e&&0!==e||n++}return 3*n},e.getPenaltyN3=function(t){const e=t.size;let n=0,r=0,i=0;for(let o=0;o<e;o++){r=i=0;for(let a=0;a<e;a++)r=r<<1&2047|t.get(o,a),a>=10&&(1488===r||93===r)&&n++,i=i<<1&2047|t.get(a,o),a>=10&&(1488===i||93===i)&&n++}return 40*n},e.getPenaltyN4=function(t){let e=0;const n=t.data.length;for(let r=0;r<n;r++)e+=t.data[r];return 10*Math.abs(Math.ceil(100*e/n/5)-10)},e.applyMask=function(t,e){const r=e.size;for(let i=0;i<r;i++)for(let o=0;o<r;o++)e.isReserved(o,i)||e.xor(o,i,n(t,o,i))},e.getBestMask=function(t,n){const r=Object.keys(e.Patterns).length;let i=0,o=1/0;for(let a=0;a<r;a++){n(a),e.applyMask(a,t);const r=e.getPenaltyN1(t)+e.getPenaltyN2(t)+e.getPenaltyN3(t)+e.getPenaltyN4(t);e.applyMask(a,t),r<o&&(o=r,i=a)}return i}},1333:t=>{t.exports=function(){return"function"==typeof Promise&&Promise.prototype&&Promise.prototype.then}},1427:(t,e,n)=>{const r=n(6886),i=n(5137),o=n(9953),a=n(208),s=n(1878),c=r.getBCHDigit(7973);function l(t,e){return a.getCharCountIndicator(t,e)+4}function u(t,e){let n=0;return t.forEach((function(t){const r=l(t.mode,e);n+=r+t.getBitsLength()})),n}e.from=function(t,e){return s.isValid(t)?parseInt(t,10):e},e.getCapacity=function(t,e,n){if(!s.isValid(t))throw new Error("Invalid QR Code version");void 0===n&&(n=a.BYTE);const o=8*(r.getSymbolTotalCodewords(t)-i.getTotalCodewordsCount(t,e));if(n===a.MIXED)return o;const c=o-l(n,t);switch(n){case a.NUMERIC:return Math.floor(c/10*3);case a.ALPHANUMERIC:return Math.floor(c/11*2);case a.KANJI:return Math.floor(c/13);case a.BYTE:default:return Math.floor(c/8)}},e.getBestVersionForData=function(t,n){let r;const i=o.from(n,o.M);if(Array.isArray(t)){if(t.length>1)return function(t,n){for(let r=1;r<=40;r++)if(u(t,r)<=e.getCapacity(r,n,a.MIXED))return r}(t,i);if(0===t.length)return 1;r=t[0]}else r=t;return function(t,n,r){for(let i=1;i<=40;i++)if(n<=e.getCapacity(i,r,t))return i}(r.mode,r.getLength(),i)},e.getEncodedBits=function(t){if(!s.isValid(t)||t<7)throw new Error("Invalid QR Code version");let e=t<<12;for(;r.getBCHDigit(e)-c>=0;)e^=7973<<r.getBCHDigit(e)-c;return t<<12|e}},1433:(t,e,n)=>{const r=n(208),i=["0","1","2","3","4","5","6","7","8","9","A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z"," ","$","%","*","+","-",".","/",":"];function o(t){this.mode=r.ALPHANUMERIC,this.data=t}o.getBitsLength=function(t){return 11*Math.floor(t/2)+t%2*6},o.prototype.getLength=function(){return this.data.length},o.prototype.getBitsLength=function(){return o.getBitsLength(this.data.length)},o.prototype.write=function(t){let e;for(e=0;e+2<=this.data.length;e+=2){let n=45*i.indexOf(this.data[e]);n+=i.indexOf(this.data[e+1]),t.put(n,11)}this.data.length%2&&t.put(i.indexOf(this.data[e]),6)},t.exports=o},1878:(t,e)=>{e.isValid=function(t){return!isNaN(t)&&t>=1&&t<=40}},2132:(t,e,n)=>{"use strict";var r=n(2618),i=n(5707),o=n(6752),a=n(8504),s=n(6201);class c{constructor(t){this.Y=t}disconnect(){this.Y=void 0}reconnect(t){this.Y=t}deref(){return this.Y}}class l{constructor(){this.Z=void 0,this.q=void 0}get(){return this.Z}pause(){this.Z??=new Promise((t=>this.q=t))}resume(){this.q?.(),this.Z=this.q=void 0}}var u=n(7804);const h=t=>!(0,a.sO)(t)&&"function"==typeof t.then,d=1073741823;class g extends s.Kq{constructor(){super(...arguments),this._$Cwt=d,this._$Cbt=[],this._$CK=new c(this),this._$CX=new l}render(...t){return t.find((t=>!h(t)))??o.c0}update(t,e){const n=this._$Cbt;let r=n.length;this._$Cbt=e;const i=this._$CK,a=this._$CX;this.isConnected||this.disconnected();for(let t=0;t<e.length&&!(t>this._$Cwt);t++){const o=e[t];if(!h(o))return this._$Cwt=t,o;t<r&&o===n[t]||(this._$Cwt=d,r=0,Promise.resolve(o).then((async t=>{for(;a.get();)await a.get();const e=i.deref();if(void 0!==e){const n=e._$Cbt.indexOf(o);n>-1&&n<e._$Cwt&&(e._$Cwt=n,e.setValue(t))}})))}return o.c0}disconnected(){this._$CK.disconnect(),this._$CX.pause()}reconnected(){this._$CK.reconnect(this),this._$CX.resume()}}const f=(0,u.u$)(g),p=new class{constructor(){this.cache=new Map}set(t,e){this.cache.set(t,e)}get(t){return this.cache.get(t)}has(t){return this.cache.has(t)}delete(t){this.cache.delete(t)}clear(){this.cache.clear()}};var w=n(6109),v=n(3494);const y=r.AH`
  :host {
    display: flex;
    aspect-ratio: var(--local-aspect-ratio);
    color: var(--local-color);
    width: var(--local-width);
  }

  svg {
    width: inherit;
    height: inherit;
    object-fit: contain;
    object-position: center;
  }

  .fallback {
    width: var(--local-width);
    height: var(--local-height);
  }
`;var m=function(t,e,n,r){var i,o=arguments.length,a=o<3?e:null===r?r=Object.getOwnPropertyDescriptor(e,n):r;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)a=Reflect.decorate(t,e,n,r);else for(var s=t.length-1;s>=0;s--)(i=t[s])&&(a=(o<3?i(a):o>3?i(e,n,a):i(e,n))||a);return o>3&&a&&Object.defineProperty(e,n,a),a};const b={add:async()=>(await n.e(1476).then(n.bind(n,1476))).addSvg,allWallets:async()=>(await n.e(3723).then(n.bind(n,3723))).allWalletsSvg,arrowBottomCircle:async()=>(await n.e(6717).then(n.bind(n,6717))).arrowBottomCircleSvg,appStore:async()=>(await n.e(9236).then(n.bind(n,9236))).appStoreSvg,apple:async()=>(await n.e(1979).then(n.bind(n,1979))).appleSvg,arrowBottom:async()=>(await n.e(5776).then(n.bind(n,5776))).arrowBottomSvg,arrowLeft:async()=>(await n.e(6426).then(n.bind(n,6426))).arrowLeftSvg,arrowRight:async()=>(await n.e(5133).then(n.bind(n,5133))).arrowRightSvg,arrowTop:async()=>(await n.e(6040).then(n.bind(n,6040))).arrowTopSvg,bank:async()=>(await n.e(261).then(n.bind(n,261))).bankSvg,browser:async()=>(await n.e(787).then(n.bind(n,787))).browserSvg,card:async()=>(await n.e(1029).then(n.bind(n,1029))).cardSvg,checkmark:async()=>(await n.e(9390).then(n.bind(n,9390))).checkmarkSvg,checkmarkBold:async()=>(await n.e(1824).then(n.bind(n,1824))).checkmarkBoldSvg,chevronBottom:async()=>(await n.e(5214).then(n.bind(n,5214))).chevronBottomSvg,chevronLeft:async()=>(await n.e(5664).then(n.bind(n,5664))).chevronLeftSvg,chevronRight:async()=>(await n.e(2387).then(n.bind(n,2387))).chevronRightSvg,chevronTop:async()=>(await n.e(9146).then(n.bind(n,9146))).chevronTopSvg,chromeStore:async()=>(await n.e(2565).then(n.bind(n,2565))).chromeStoreSvg,clock:async()=>(await n.e(1837).then(n.bind(n,1837))).clockSvg,close:async()=>(await n.e(5943).then(n.bind(n,5943))).closeSvg,compass:async()=>(await n.e(2011).then(n.bind(n,2011))).compassSvg,coinPlaceholder:async()=>(await n.e(6929).then(n.bind(n,6929))).coinPlaceholderSvg,copy:async()=>(await n.e(4554).then(n.bind(n,4554))).copySvg,cursor:async()=>(await n.e(2161).then(n.bind(n,2161))).cursorSvg,cursorTransparent:async()=>(await n.e(5518).then(n.bind(n,5518))).cursorTransparentSvg,desktop:async()=>(await n.e(6355).then(n.bind(n,6355))).desktopSvg,disconnect:async()=>(await n.e(4953).then(n.bind(n,4953))).disconnectSvg,discord:async()=>(await n.e(7243).then(n.bind(n,7243))).discordSvg,etherscan:async()=>(await n.e(70).then(n.bind(n,70))).etherscanSvg,extension:async()=>(await n.e(6618).then(n.bind(n,6618))).extensionSvg,externalLink:async()=>(await n.e(877).then(n.bind(n,877))).externalLinkSvg,facebook:async()=>(await n.e(279).then(n.bind(n,279))).facebookSvg,farcaster:async()=>(await n.e(5426).then(n.bind(n,5426))).farcasterSvg,filters:async()=>(await n.e(4052).then(n.bind(n,4052))).filtersSvg,github:async()=>(await n.e(1496).then(n.bind(n,1496))).githubSvg,google:async()=>(await n.e(9624).then(n.bind(n,9624))).googleSvg,helpCircle:async()=>(await n.e(6561).then(n.bind(n,6561))).helpCircleSvg,image:async()=>(await n.e(8842).then(n.bind(n,8842))).imageSvg,id:async()=>(await n.e(4778).then(n.bind(n,4778))).idSvg,infoCircle:async()=>(await n.e(4748).then(n.bind(n,4748))).infoCircleSvg,lightbulb:async()=>(await n.e(6828).then(n.bind(n,6828))).lightbulbSvg,mail:async()=>(await n.e(2688).then(n.bind(n,2688))).mailSvg,mobile:async()=>(await n.e(9385).then(n.bind(n,9385))).mobileSvg,more:async()=>(await n.e(4230).then(n.bind(n,4230))).moreSvg,networkPlaceholder:async()=>(await n.e(2901).then(n.bind(n,2901))).networkPlaceholderSvg,nftPlaceholder:async()=>(await n.e(5410).then(n.bind(n,5410))).nftPlaceholderSvg,off:async()=>(await n.e(2658).then(n.bind(n,2658))).offSvg,playStore:async()=>(await n.e(7469).then(n.bind(n,7469))).playStoreSvg,plus:async()=>(await n.e(1035).then(n.bind(n,1035))).plusSvg,qrCode:async()=>(await n.e(2016).then(n.bind(n,2016))).qrCodeIcon,recycleHorizontal:async()=>(await n.e(4987).then(n.bind(n,4987))).recycleHorizontalSvg,refresh:async()=>(await n.e(5452).then(n.bind(n,5452))).refreshSvg,search:async()=>(await n.e(8127).then(n.bind(n,8127))).searchSvg,send:async()=>(await n.e(4725).then(n.bind(n,4725))).sendSvg,swapHorizontal:async()=>(await n.e(6780).then(n.bind(n,6780))).swapHorizontalSvg,swapHorizontalMedium:async()=>(await n.e(1975).then(n.bind(n,1975))).swapHorizontalMediumSvg,swapHorizontalBold:async()=>(await n.e(3967).then(n.bind(n,3967))).swapHorizontalBoldSvg,swapHorizontalRoundedBold:async()=>(await n.e(6188).then(n.bind(n,6188))).swapHorizontalRoundedBoldSvg,swapVertical:async()=>(await n.e(1538).then(n.bind(n,1538))).swapVerticalSvg,telegram:async()=>(await n.e(2692).then(n.bind(n,2692))).telegramSvg,threeDots:async()=>(await n.e(5420).then(n.bind(n,5420))).threeDotsSvg,twitch:async()=>(await n.e(4736).then(n.bind(n,4736))).twitchSvg,twitter:async()=>(await n.e(2931).then(n.bind(n,2931))).xSvg,twitterIcon:async()=>(await n.e(4477).then(n.bind(n,4477))).twitterIconSvg,verify:async()=>(await n.e(2026).then(n.bind(n,2026))).verifySvg,verifyFilled:async()=>(await n.e(4067).then(n.bind(n,4067))).verifyFilledSvg,wallet:async()=>(await n.e(6530).then(n.bind(n,6530))).walletSvg,walletConnect:async()=>(await n.e(5806).then(n.bind(n,5806))).walletConnectSvg,walletConnectLightBrown:async()=>(await n.e(5806).then(n.bind(n,5806))).walletConnectLightBrownSvg,walletConnectBrown:async()=>(await n.e(5806).then(n.bind(n,5806))).walletConnectBrownSvg,walletPlaceholder:async()=>(await n.e(4714).then(n.bind(n,4714))).walletPlaceholderSvg,warningCircle:async()=>(await n.e(6348).then(n.bind(n,6348))).warningCircleSvg,x:async()=>(await n.e(2931).then(n.bind(n,2931))).xSvg,info:async()=>(await n.e(5823).then(n.bind(n,5823))).infoSvg,exclamationTriangle:async()=>(await n.e(5179).then(n.bind(n,5179))).exclamationTriangleSvg,reown:async()=>(await n.e(1978).then(n.bind(n,1978))).reownSvg};let x=class extends r.WF{constructor(){super(...arguments),this.size="md",this.name="copy",this.color="fg-300",this.aspectRatio="1 / 1"}render(){return this.style.cssText=`\n      --local-color: var(--wui-color-${this.color});\n      --local-width: var(--wui-icon-size-${this.size});\n      --local-aspect-ratio: ${this.aspectRatio}\n    `,r.qy`${f(async function(t){if(p.has(t))return p.get(t);const e=(b[t]??b.copy)();return p.set(t,e),e}(this.name),r.qy`<div class="fallback"></div>`)}`}};x.styles=[w.W5,w.ck,y],m([(0,i.MZ)()],x.prototype,"size",void 0),m([(0,i.MZ)()],x.prototype,"name",void 0),m([(0,i.MZ)()],x.prototype,"color",void 0),m([(0,i.MZ)()],x.prototype,"aspectRatio",void 0),x=m([(0,v.E)("wui-icon")],x)},2726:(t,e)=>{function n(t){if("number"==typeof t&&(t=t.toString()),"string"!=typeof t)throw new Error("Color should be defined as hex string");let e=t.slice().replace("#","").split("");if(e.length<3||5===e.length||e.length>8)throw new Error("Invalid hex color: "+t);3!==e.length&&4!==e.length||(e=Array.prototype.concat.apply([],e.map((function(t){return[t,t]})))),6===e.length&&e.push("F","F");const n=parseInt(e.join(""),16);return{r:n>>24&255,g:n>>16&255,b:n>>8&255,a:255&n,hex:"#"+e.slice(0,6).join("")}}e.getOptions=function(t){t||(t={}),t.color||(t.color={});const e=void 0===t.margin||null===t.margin||t.margin<0?4:t.margin,r=t.width&&t.width>=21?t.width:void 0,i=t.scale||4;return{width:r,scale:r?4:i,margin:e,color:{dark:n(t.color.dark||"#000000ff"),light:n(t.color.light||"#ffffffff")},type:t.type,rendererOpts:t.rendererOpts||{}}},e.getScale=function(t,e){return e.width&&e.width>=t+2*e.margin?e.width/(t+2*e.margin):e.scale},e.getImageWidth=function(t,n){const r=e.getScale(t,n);return Math.floor((t+2*n.margin)*r)},e.qrToImageData=function(t,n,r){const i=n.modules.size,o=n.modules.data,a=e.getScale(i,r),s=Math.floor((i+2*r.margin)*a),c=r.margin*a,l=[r.color.light,r.color.dark];for(let e=0;e<s;e++)for(let n=0;n<s;n++){let u=4*(e*s+n),h=r.color.light;e>=c&&n>=c&&e<s-c&&n<s-c&&(h=l[o[Math.floor((e-c)/a)*i+Math.floor((n-c)/a)]?1:0]),t[u++]=h.r,t[u++]=h.g,t[u++]=h.b,t[u]=h.a}}},2731:(t,e)=>{const n=new Uint8Array(512),r=new Uint8Array(256);!function(){let t=1;for(let e=0;e<255;e++)n[e]=t,r[t]=e,t<<=1,256&t&&(t^=285);for(let t=255;t<512;t++)n[t]=n[t-255]}(),e.log=function(t){if(t<1)throw new Error("log("+t+")");return r[t]},e.exp=function(t){return n[t]},e.mul=function(t,e){return 0===t||0===e?0:n[r[t]+r[e]]}},3720:(t,e,n)=>{"use strict";n.d(e,{H:()=>o});var r=n(6752),i=n(7804);const o=(0,i.u$)(class extends i.WL{constructor(t){if(super(t),t.type!==i.OA.ATTRIBUTE||"class"!==t.name||t.strings?.length>2)throw Error("`classMap()` can only be used in the `class` attribute and must be the only part in the attribute.")}render(t){return" "+Object.keys(t).filter((e=>t[e])).join(" ")+" "}update(t,[e]){if(void 0===this.st){this.st=new Set,void 0!==t.strings&&(this.nt=new Set(t.strings.join(" ").split(/\s/).filter((t=>""!==t))));for(const t in e)e[t]&&!this.nt?.has(t)&&this.st.add(t);return this.render(e)}const n=t.element.classList;for(const t of this.st)t in e||(n.remove(t),this.st.delete(t));for(const t in e){const r=!!e[t];r===this.st.has(t)||this.nt?.has(t)||(r?(n.add(t),this.st.add(t)):(n.remove(t),this.st.delete(t)))}return r.c0}})},4290:(t,e,n)=>{"use strict";n.d(e,{w:()=>i});var r=n(5694);function i(t){return(0,r.M)({...t,state:!0,attribute:!1})}},4357:(t,e,n)=>{const r=n(208);function i(t){this.mode=r.NUMERIC,this.data=t.toString()}i.getBitsLength=function(t){return 10*Math.floor(t/3)+(t%3?t%3*3+1:0)},i.prototype.getLength=function(){return this.data.length},i.prototype.getBitsLength=function(){return i.getBitsLength(this.data.length)},i.prototype.write=function(t){let e,n,r;for(e=0;e+3<=this.data.length;e+=3)n=this.data.substr(e,3),r=parseInt(n,10),t.put(r,10);const i=this.data.length-e;i>0&&(n=this.data.substr(e),r=parseInt(n,10),t.put(r,3*i+1))},t.exports=i},4565:(t,e,n)=>{const r=n(6886),i=r.getBCHDigit(1335);e.getEncodedBits=function(t,e){const n=t.bit<<3|e;let o=n<<10;for(;r.getBCHDigit(o)-i>=0;)o^=1335<<r.getBCHDigit(o)-i;return 21522^(n<<10|o)}},4713:(t,e,n)=>{const r=n(2731);e.mul=function(t,e){const n=new Uint8Array(t.length+e.length-1);for(let i=0;i<t.length;i++)for(let o=0;o<e.length;o++)n[i+o]^=r.mul(t[i],e[o]);return n},e.mod=function(t,e){let n=new Uint8Array(t);for(;n.length-e.length>=0;){const t=n[0];for(let i=0;i<e.length;i++)n[i]^=r.mul(e[i],t);let i=0;for(;i<n.length&&0===n[i];)i++;n=n.slice(i)}return n},e.generateECPolynomial=function(t){let n=new Uint8Array([1]);for(let i=0;i<t;i++)n=e.mul(n,new Uint8Array([1,r.exp(i)]));return n}},4764:(t,e,n)=>{const r=n(4713);function i(t){this.genPoly=void 0,this.degree=t,this.degree&&this.initialize(this.degree)}i.prototype.initialize=function(t){this.degree=t,this.genPoly=r.generateECPolynomial(this.degree)},i.prototype.encode=function(t){if(!this.genPoly)throw new Error("Encoder not initialized");const e=new Uint8Array(t.length+this.degree);e.set(t);const n=r.mod(e,this.genPoly),i=this.degree-n.length;if(i>0){const t=new Uint8Array(this.degree);return t.set(n,i),t}return n},t.exports=i},4861:(t,e,n)=>{const r=n(208),i=n(6886);function o(t){this.mode=r.KANJI,this.data=t}o.getBitsLength=function(t){return 13*t},o.prototype.getLength=function(){return this.data.length},o.prototype.getBitsLength=function(){return o.getBitsLength(this.data.length)},o.prototype.write=function(t){let e;for(e=0;e<this.data.length;e++){let n=i.toSJIS(this.data[e]);if(n>=33088&&n<=40956)n-=33088;else{if(!(n>=57408&&n<=60351))throw new Error("Invalid SJIS character: "+this.data[e]+"\nMake sure your charset is UTF-8");n-=49472}n=192*(n>>>8&255)+(255&n),t.put(n,13)}},t.exports=o},5090:(t,e,n)=>{"use strict";n(8409)},5137:(t,e,n)=>{const r=n(9953),i=[1,1,1,1,1,1,1,1,1,1,2,2,1,2,2,4,1,2,4,4,2,4,4,4,2,4,6,5,2,4,6,6,2,5,8,8,4,5,8,8,4,5,8,11,4,8,10,11,4,9,12,16,4,9,16,16,6,10,12,18,6,10,17,16,6,11,16,19,6,13,18,21,7,14,21,25,8,16,20,25,8,17,23,25,9,17,23,34,9,18,25,30,10,20,27,32,12,21,29,35,12,23,34,37,12,25,34,40,13,26,35,42,14,28,38,45,15,29,40,48,16,31,43,51,17,33,45,54,18,35,48,57,19,37,51,60,19,38,53,63,20,40,56,66,21,43,59,70,22,45,62,74,24,47,65,77,25,49,68,81],o=[7,10,13,17,10,16,22,28,15,26,36,44,20,36,52,64,26,48,72,88,36,64,96,112,40,72,108,130,48,88,132,156,60,110,160,192,72,130,192,224,80,150,224,264,96,176,260,308,104,198,288,352,120,216,320,384,132,240,360,432,144,280,408,480,168,308,448,532,180,338,504,588,196,364,546,650,224,416,600,700,224,442,644,750,252,476,690,816,270,504,750,900,300,560,810,960,312,588,870,1050,336,644,952,1110,360,700,1020,1200,390,728,1050,1260,420,784,1140,1350,450,812,1200,1440,480,868,1290,1530,510,924,1350,1620,540,980,1440,1710,570,1036,1530,1800,570,1064,1590,1890,600,1120,1680,1980,630,1204,1770,2100,660,1260,1860,2220,720,1316,1950,2310,750,1372,2040,2430];e.getBlocksCount=function(t,e){switch(e){case r.L:return i[4*(t-1)+0];case r.M:return i[4*(t-1)+1];case r.Q:return i[4*(t-1)+2];case r.H:return i[4*(t-1)+3];default:return}},e.getTotalCodewordsCount=function(t,e){switch(e){case r.L:return o[4*(t-1)+0];case r.M:return o[4*(t-1)+1];case r.Q:return o[4*(t-1)+2];case r.H:return o[4*(t-1)+3];default:return}}},5694:(t,e,n)=>{"use strict";n.d(e,{M:()=>a});var r=n(842);const i={attribute:!0,type:String,converter:r.W3,reflect:!1,hasChanged:r.Ec},o=(t=i,e,n)=>{const{kind:r,metadata:o}=n;let a=globalThis.litPropertyMetadata.get(o);if(void 0===a&&globalThis.litPropertyMetadata.set(o,a=new Map),a.set(n.name,t),"accessor"===r){const{name:r}=n;return{set(n){const i=e.get.call(this);e.set.call(this,n),this.requestUpdate(r,i,t)},init(e){return void 0!==e&&this.P(r,void 0,t),e}}}if("setter"===r){const{name:r}=n;return function(n){const i=this[r];e.call(this,n),this.requestUpdate(r,i,t)}}throw Error("Unsupported decorator location: "+r)};function a(t){return(e,n)=>"object"==typeof n?o(t,e,n):((t,e,n)=>{const r=e.hasOwnProperty(n);return e.constructor.createProperty(n,r?{...t,wrapped:!0}:t),r?Object.getOwnPropertyDescriptor(e,n):void 0})(t,e,n)}},5707:(t,e,n)=>{"use strict";n.d(e,{MZ:()=>r.M,wk:()=>i.w});var r=n(5694),i=n(4290)},5822:(t,e,n)=>{const r=n(9049),i=n(208);function o(t){this.mode=i.BYTE,"string"==typeof t&&(t=r(t)),this.data=new Uint8Array(t)}o.getBitsLength=function(t){return 8*t},o.prototype.getLength=function(){return this.data.length},o.prototype.getBitsLength=function(){return o.getBitsLength(this.data.length)},o.prototype.write=function(t){for(let e=0,n=this.data.length;e<n;e++)t.put(this.data[e],8)},t.exports=o},5999:(t,e,n)=>{"use strict";var r=n(2618),i=n(5707),o=(n(2132),n(6887),n(8409),n(6109)),a=n(3494);const s=r.AH`
  button {
    border: none;
    border-radius: var(--wui-border-radius-3xl);
  }

  button[data-variant='main'] {
    background-color: var(--wui-color-accent-100);
    color: var(--wui-color-inverse-100);
    box-shadow: inset 0 0 0 1px var(--wui-color-gray-glass-010);
  }

  button[data-variant='accent'] {
    background-color: var(--wui-color-accent-glass-010);
    color: var(--wui-color-accent-100);
    box-shadow: inset 0 0 0 1px var(--wui-color-gray-glass-005);
  }

  button[data-variant='gray'] {
    background-color: transparent;
    color: var(--wui-color-fg-200);
    box-shadow: inset 0 0 0 1px var(--wui-color-gray-glass-010);
  }

  button[data-variant='shade'] {
    background-color: transparent;
    color: var(--wui-color-accent-100);
    box-shadow: inset 0 0 0 1px var(--wui-color-gray-glass-010);
  }

  button[data-size='sm'] {
    height: 32px;
    padding: 0 var(--wui-spacing-s);
  }

  button[data-size='md'] {
    height: 40px;
    padding: 0 var(--wui-spacing-l);
  }

  button[data-size='sm'] > wui-image {
    width: 16px;
    height: 16px;
  }

  button[data-size='md'] > wui-image {
    width: 24px;
    height: 24px;
  }

  button[data-size='sm'] > wui-icon {
    width: 12px;
    height: 12px;
  }

  button[data-size='md'] > wui-icon {
    width: 14px;
    height: 14px;
  }

  wui-image {
    border-radius: var(--wui-border-radius-3xl);
    overflow: hidden;
  }

  button.disabled > wui-icon,
  button.disabled > wui-image {
    filter: grayscale(1);
  }

  button[data-variant='main'] > wui-image {
    box-shadow: inset 0 0 0 1px var(--wui-color-accent-090);
  }

  button[data-variant='shade'] > wui-image,
  button[data-variant='gray'] > wui-image {
    box-shadow: inset 0 0 0 1px var(--wui-color-gray-glass-010);
  }

  @media (hover: hover) and (pointer: fine) {
    button[data-variant='main']:focus-visible {
      background-color: var(--wui-color-accent-090);
    }

    button[data-variant='main']:hover:enabled {
      background-color: var(--wui-color-accent-090);
    }

    button[data-variant='main']:active:enabled {
      background-color: var(--wui-color-accent-080);
    }

    button[data-variant='accent']:hover:enabled {
      background-color: var(--wui-color-accent-glass-015);
    }

    button[data-variant='accent']:active:enabled {
      background-color: var(--wui-color-accent-glass-020);
    }

    button[data-variant='shade']:focus-visible,
    button[data-variant='gray']:focus-visible,
    button[data-variant='shade']:hover,
    button[data-variant='gray']:hover {
      background-color: var(--wui-color-gray-glass-002);
    }

    button[data-variant='gray']:active,
    button[data-variant='shade']:active {
      background-color: var(--wui-color-gray-glass-005);
    }
  }

  button.disabled {
    color: var(--wui-color-gray-glass-020);
    background-color: var(--wui-color-gray-glass-002);
    box-shadow: inset 0 0 0 1px var(--wui-color-gray-glass-002);
    pointer-events: none;
  }
`;var c=function(t,e,n,r){var i,o=arguments.length,a=o<3?e:null===r?r=Object.getOwnPropertyDescriptor(e,n):r;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)a=Reflect.decorate(t,e,n,r);else for(var s=t.length-1;s>=0;s--)(i=t[s])&&(a=(o<3?i(a):o>3?i(e,n,a):i(e,n))||a);return o>3&&a&&Object.defineProperty(e,n,a),a};let l=class extends r.WF{constructor(){super(...arguments),this.variant="accent",this.imageSrc="",this.disabled=!1,this.icon="externalLink",this.size="md",this.text=""}render(){const t="sm"===this.size?"small-600":"paragraph-600";return r.qy`
      <button
        class=${this.disabled?"disabled":""}
        data-variant=${this.variant}
        data-size=${this.size}
      >
        ${this.imageSrc?r.qy`<wui-image src=${this.imageSrc}></wui-image>`:null}
        <wui-text variant=${t} color="inherit"> ${this.text} </wui-text>
        <wui-icon name=${this.icon} color="inherit" size="inherit"></wui-icon>
      </button>
    `}};l.styles=[o.W5,o.fD,s],c([(0,i.MZ)()],l.prototype,"variant",void 0),c([(0,i.MZ)()],l.prototype,"imageSrc",void 0),c([(0,i.MZ)({type:Boolean})],l.prototype,"disabled",void 0),c([(0,i.MZ)()],l.prototype,"icon",void 0),c([(0,i.MZ)()],l.prototype,"size",void 0),c([(0,i.MZ)()],l.prototype,"text",void 0),l=c([(0,a.E)("wui-chip-button")],l)},6201:(t,e,n)=>{"use strict";n.d(e,{Kq:()=>h});var r=n(8504),i=n(7804);const o=(t,e)=>{const n=t._$AN;if(void 0===n)return!1;for(const t of n)t._$AO?.(e,!1),o(t,e);return!0},a=t=>{let e,n;do{if(void 0===(e=t._$AM))break;n=e._$AN,n.delete(t),t=e}while(0===n?.size)},s=t=>{for(let e;e=t._$AM;t=e){let n=e._$AN;if(void 0===n)e._$AN=n=new Set;else if(n.has(t))break;n.add(t),u(e)}};function c(t){void 0!==this._$AN?(a(this),this._$AM=t,s(this)):this._$AM=t}function l(t,e=!1,n=0){const r=this._$AH,i=this._$AN;if(void 0!==i&&0!==i.size)if(e)if(Array.isArray(r))for(let t=n;t<r.length;t++)o(r[t],!1),a(r[t]);else null!=r&&(o(r,!1),a(r));else o(this,t)}const u=t=>{t.type==i.OA.CHILD&&(t._$AP??=l,t._$AQ??=c)};class h extends i.WL{constructor(){super(...arguments),this._$AN=void 0}_$AT(t,e,n){super._$AT(t,e,n),s(this),this.isConnected=t._$AU}_$AO(t,e=!0){t!==this.isConnected&&(this.isConnected=t,t?this.reconnected?.():this.disconnected?.()),e&&(o(this,t),a(this))}setValue(t){if((0,r.Rt)(this._$Ct))this._$Ct._$AI(t,this);else{const e=[...this._$Ct._$AH];e[this._$Ci]=t,this._$Ct._$AI(e,this,0)}}disconnected(){}reconnected(){}}},6283:(t,e,n)=>{"use strict";var r=n(2618),i=n(5707),o=(n(2132),n(6887),n(7583));function a(t,e,n){return t!==e&&(t-e<0?e-t:t-e)<=n+.1}const s={generate({uri:t,size:e,logoSize:n,dotColor:i="#141414"}){const s=[],c=function(t){const e=Array.prototype.slice.call(o.create(t,{errorCorrectionLevel:"Q"}).modules.data,0),n=Math.sqrt(e.length);return e.reduce(((t,e,r)=>(r%n==0?t.push([e]):t[t.length-1].push(e))&&t),[])}(t),l=e/c.length,u=[{x:0,y:0},{x:1,y:0},{x:0,y:1}];u.forEach((({x:t,y:e})=>{const n=(c.length-7)*l*t,o=(c.length-7)*l*e,a=.45;for(let t=0;t<u.length;t+=1){const e=l*(7-2*t);s.push(r.JW`
            <rect
              fill=${2===t?i:"transparent"}
              width=${0===t?e-5:e}
              rx= ${0===t?(e-5)*a:e*a}
              ry= ${0===t?(e-5)*a:e*a}
              stroke=${i}
              stroke-width=${0===t?5:0}
              height=${0===t?e-5:e}
              x= ${0===t?o+l*t+2.5:o+l*t}
              y= ${0===t?n+l*t+2.5:n+l*t}
            />
          `)}}));const h=Math.floor((n+25)/l),d=c.length/2-h/2,g=c.length/2+h/2-1,f=[];c.forEach(((t,e)=>{t.forEach(((t,n)=>{if(c[e][n]&&!(e<7&&n<7||e>c.length-8&&n<7||e<7&&n>c.length-8||e>d&&e<g&&n>d&&n<g)){const t=e*l+l/2,r=n*l+l/2;f.push([t,r])}}))}));const p={};return f.forEach((([t,e])=>{p[t]?p[t]?.push(e):p[t]=[e]})),Object.entries(p).map((([t,e])=>{const n=e.filter((t=>e.every((e=>!a(t,e,l)))));return[Number(t),n]})).forEach((([t,e])=>{e.forEach((e=>{s.push(r.JW`<circle cx=${t} cy=${e} fill=${i} r=${l/2.5} />`)}))})),Object.entries(p).filter((([t,e])=>e.length>1)).map((([t,e])=>{const n=e.filter((t=>e.some((e=>a(t,e,l)))));return[Number(t),n]})).map((([t,e])=>{e.sort(((t,e)=>t<e?-1:1));const n=[];for(const t of e){const e=n.find((e=>e.some((e=>a(t,e,l)))));e?e.push(t):n.push([t])}return[t,n.map((t=>[t[0],t[t.length-1]]))]})).forEach((([t,e])=>{e.forEach((([e,n])=>{s.push(r.JW`
              <line
                x1=${t}
                x2=${t}
                y1=${e}
                y2=${n}
                stroke=${i}
                stroke-width=${l/1.25}
                stroke-linecap="round"
              />
            `)}))})),s}};var c=n(6109),l=n(3494);const u=r.AH`
  :host {
    position: relative;
    user-select: none;
    display: block;
    overflow: hidden;
    aspect-ratio: 1 / 1;
    width: var(--local-size);
  }

  :host([data-theme='dark']) {
    border-radius: clamp(0px, var(--wui-border-radius-l), 40px);
    background-color: var(--wui-color-inverse-100);
    padding: var(--wui-spacing-l);
  }

  :host([data-theme='light']) {
    box-shadow: 0 0 0 1px var(--wui-color-bg-125);
    background-color: var(--wui-color-bg-125);
  }

  :host([data-clear='true']) > wui-icon {
    display: none;
  }

  svg:first-child,
  wui-image,
  wui-icon {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translateY(-50%) translateX(-50%);
  }

  wui-image {
    width: 25%;
    height: 25%;
    border-radius: var(--wui-border-radius-xs);
  }

  wui-icon {
    width: 100%;
    height: 100%;
    color: var(--local-icon-color) !important;
    transform: translateY(-50%) translateX(-50%) scale(0.25);
  }
`;var h=function(t,e,n,r){var i,o=arguments.length,a=o<3?e:null===r?r=Object.getOwnPropertyDescriptor(e,n):r;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)a=Reflect.decorate(t,e,n,r);else for(var s=t.length-1;s>=0;s--)(i=t[s])&&(a=(o<3?i(a):o>3?i(e,n,a):i(e,n))||a);return o>3&&a&&Object.defineProperty(e,n,a),a};let d=class extends r.WF{constructor(){super(...arguments),this.uri="",this.size=0,this.theme="dark",this.imageSrc=void 0,this.alt=void 0,this.arenaClear=void 0,this.farcaster=void 0}render(){return this.dataset.theme=this.theme,this.dataset.clear=String(this.arenaClear),this.style.cssText=`\n     --local-size: ${this.size}px;\n     --local-icon-color: ${this.color??"#3396ff"}\n    `,r.qy`${this.templateVisual()} ${this.templateSvg()}`}templateSvg(){const t="light"===this.theme?this.size:this.size-32;return r.JW`
      <svg height=${t} width=${t}>
        ${s.generate({uri:this.uri,size:t,logoSize:this.arenaClear?0:t/4,dotColor:this.color})}
      </svg>
    `}templateVisual(){return this.imageSrc?r.qy`<wui-image src=${this.imageSrc} alt=${this.alt??"logo"}></wui-image>`:this.farcaster?r.qy`<wui-icon
        class="farcaster"
        size="inherit"
        color="inherit"
        name="farcaster"
      ></wui-icon>`:r.qy`<wui-icon size="inherit" color="inherit" name="walletConnect"></wui-icon>`}};d.styles=[c.W5,u],h([(0,i.MZ)()],d.prototype,"uri",void 0),h([(0,i.MZ)({type:Number})],d.prototype,"size",void 0),h([(0,i.MZ)()],d.prototype,"theme",void 0),h([(0,i.MZ)()],d.prototype,"imageSrc",void 0),h([(0,i.MZ)()],d.prototype,"alt",void 0),h([(0,i.MZ)()],d.prototype,"color",void 0),h([(0,i.MZ)({type:Boolean})],d.prototype,"arenaClear",void 0),h([(0,i.MZ)({type:Boolean})],d.prototype,"farcaster",void 0),d=h([(0,l.E)("wui-qr-code")],d)},6320:t=>{"use strict";var e={single_source_shortest_paths:function(t,n,r){var i={},o={};o[n]=0;var a,s,c,l,u,h,d,g=e.PriorityQueue.make();for(g.push(n,0);!g.empty();)for(c in s=(a=g.pop()).value,l=a.cost,u=t[s]||{})u.hasOwnProperty(c)&&(h=l+u[c],d=o[c],(void 0===o[c]||d>h)&&(o[c]=h,g.push(c,h),i[c]=s));if(void 0!==r&&void 0===o[r]){var f=["Could not find a path from ",n," to ",r,"."].join("");throw new Error(f)}return i},extract_shortest_path_from_predecessor_list:function(t,e){for(var n=[],r=e;r;)n.push(r),t[r],r=t[r];return n.reverse(),n},find_path:function(t,n,r){var i=e.single_source_shortest_paths(t,n,r);return e.extract_shortest_path_from_predecessor_list(i,r)},PriorityQueue:{make:function(t){var n,r=e.PriorityQueue,i={};for(n in t=t||{},r)r.hasOwnProperty(n)&&(i[n]=r[n]);return i.queue=[],i.sorter=t.sorter||r.default_sorter,i},default_sorter:function(t,e){return t.cost-e.cost},push:function(t,e){var n={value:t,cost:e};this.queue.push(n),this.queue.sort(this.sorter)},pop:function(){return this.queue.shift()},empty:function(){return 0===this.queue.length}}};t.exports=e},6421:(t,e,n)=>{const r=n(6886).getSymbolSize;e.getRowColCoords=function(t){if(1===t)return[];const e=Math.floor(t/7)+2,n=r(t),i=145===n?26:2*Math.ceil((n-13)/(2*e-2)),o=[n-7];for(let t=1;t<e-1;t++)o[t]=o[t-1]-i;return o.push(6),o.reverse()},e.getPositions=function(t){const n=[],r=e.getRowColCoords(t),i=r.length;for(let t=0;t<i;t++)for(let e=0;e<i;e++)0===t&&0===e||0===t&&e===i-1||t===i-1&&0===e||n.push([r[t],r[e]]);return n}},6756:(t,e,n)=>{const r=n(2726);function i(t,e){const n=t.a/255,r=e+'="'+t.hex+'"';return n<1?r+" "+e+'-opacity="'+n.toFixed(2).slice(1)+'"':r}function o(t,e,n){let r=t+e;return void 0!==n&&(r+=" "+n),r}e.render=function(t,e,n){const a=r.getOptions(e),s=t.modules.size,c=t.modules.data,l=s+2*a.margin,u=a.color.light.a?"<path "+i(a.color.light,"fill")+' d="M0 0h'+l+"v"+l+'H0z"/>':"",h="<path "+i(a.color.dark,"stroke")+' d="'+function(t,e,n){let r="",i=0,a=!1,s=0;for(let c=0;c<t.length;c++){const l=Math.floor(c%e),u=Math.floor(c/e);l||a||(a=!0),t[c]?(s++,c>0&&l>0&&t[c-1]||(r+=a?o("M",l+n,.5+u+n):o("m",i,0),i=0,a=!1),l+1<e&&t[c+1]||(r+=o("h",s),s=0)):i++}return r}(c,s,a.margin)+'"/>',d='viewBox="0 0 '+l+" "+l+'"',g='<svg xmlns="http://www.w3.org/2000/svg" '+(a.width?'width="'+a.width+'" height="'+a.width+'" ':"")+d+' shape-rendering="crispEdges">'+u+h+"</svg>\n";return"function"==typeof n&&n(null,g),g}},6886:(t,e)=>{let n;const r=[0,26,44,70,100,134,172,196,242,292,346,404,466,532,581,655,733,815,901,991,1085,1156,1258,1364,1474,1588,1706,1828,1921,2051,2185,2323,2465,2611,2761,2876,3034,3196,3362,3532,3706];e.getSymbolSize=function(t){if(!t)throw new Error('"version" cannot be null or undefined');if(t<1||t>40)throw new Error('"version" should be in range from 1 to 40');return 4*t+17},e.getSymbolTotalCodewords=function(t){return r[t]},e.getBCHDigit=function(t){let e=0;for(;0!==t;)e++,t>>>=1;return e},e.setToSJISFunction=function(t){if("function"!=typeof t)throw new Error('"toSJISFunc" is not a valid function.');n=t},e.isKanjiModeEnabled=function(){return void 0!==n},e.toSJIS=function(t){return n(t)}},6887:(t,e,n)=>{"use strict";var r=n(2618),i=n(5707),o=n(6109),a=n(3494);const s=r.AH`
  :host {
    display: block;
    width: var(--local-width);
    height: var(--local-height);
  }

  img {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center center;
    border-radius: inherit;
  }
`;var c=function(t,e,n,r){var i,o=arguments.length,a=o<3?e:null===r?r=Object.getOwnPropertyDescriptor(e,n):r;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)a=Reflect.decorate(t,e,n,r);else for(var s=t.length-1;s>=0;s--)(i=t[s])&&(a=(o<3?i(a):o>3?i(e,n,a):i(e,n))||a);return o>3&&a&&Object.defineProperty(e,n,a),a};let l=class extends r.WF{constructor(){super(...arguments),this.src="./path/to/image.jpg",this.alt="Image",this.size=void 0}render(){return this.style.cssText=`\n      --local-width: ${this.size?`var(--wui-icon-size-${this.size});`:"100%"};\n      --local-height: ${this.size?`var(--wui-icon-size-${this.size});`:"100%"};\n      `,r.qy`<img src=${this.src} alt=${this.alt} @error=${this.handleImageError} />`}handleImageError(){this.dispatchEvent(new CustomEvent("onLoadError",{bubbles:!0,composed:!0}))}};l.styles=[o.W5,o.ck,s],c([(0,i.MZ)()],l.prototype,"src",void 0),c([(0,i.MZ)()],l.prototype,"alt",void 0),c([(0,i.MZ)()],l.prototype,"size",void 0),l=c([(0,a.E)("wui-image")],l)},7044:(t,e)=>{const n="[0-9]+";let r="(?:[u3000-u303F]|[u3040-u309F]|[u30A0-u30FF]|[uFF00-uFFEF]|[u4E00-u9FAF]|[u2605-u2606]|[u2190-u2195]|u203B|[u2010u2015u2018u2019u2025u2026u201Cu201Du2225u2260]|[u0391-u0451]|[u00A7u00A8u00B1u00B4u00D7u00F7])+";r=r.replace(/u/g,"\\u");const i="(?:(?![A-Z0-9 $%*+\\-./:]|"+r+")(?:.|[\r\n]))+";e.KANJI=new RegExp(r,"g"),e.BYTE_KANJI=new RegExp("[^A-Z0-9 $%*+\\-./:]+","g"),e.BYTE=new RegExp(i,"g"),e.NUMERIC=new RegExp(n,"g"),e.ALPHANUMERIC=new RegExp("[A-Z $%*+\\-./:]+","g");const o=new RegExp("^"+r+"$"),a=new RegExp("^"+n+"$"),s=new RegExp("^[A-Z0-9 $%*+\\-./:]+$");e.testKanji=function(t){return o.test(t)},e.testNumeric=function(t){return a.test(t)},e.testAlphanumeric=function(t){return s.test(t)}},7583:(t,e,n)=>{const r=n(1333),i=n(157),o=n(7899),a=n(6756);function s(t,e,n,o,a){const s=[].slice.call(arguments,1),c=s.length,l="function"==typeof s[c-1];if(!l&&!r())throw new Error("Callback required as last argument");if(!l){if(c<1)throw new Error("Too few arguments provided");return 1===c?(n=e,e=o=void 0):2!==c||e.getContext||(o=n,n=e,e=void 0),new Promise((function(r,a){try{const a=i.create(n,o);r(t(a,e,o))}catch(t){a(t)}}))}if(c<2)throw new Error("Too few arguments provided");2===c?(a=n,n=e,e=o=void 0):3===c&&(e.getContext&&void 0===a?(a=o,o=void 0):(a=o,o=n,n=e,e=void 0));try{const r=i.create(n,o);a(null,t(r,e,o))}catch(t){a(t)}}e.create=i.create,e.toCanvas=s.bind(null,o.render),e.toDataURL=s.bind(null,o.renderToDataURL),e.toString=s.bind(null,(function(t,e,n){return a.render(t,n)}))},7756:(t,e,n)=>{const r=n(6886).getSymbolSize;e.getPositions=function(t){const e=r(t);return[[0,0],[e-7,0],[0,e-7]]}},7804:(t,e,n)=>{"use strict";n.d(e,{OA:()=>r,WL:()=>o,u$:()=>i});const r={ATTRIBUTE:1,CHILD:2,PROPERTY:3,BOOLEAN_ATTRIBUTE:4,EVENT:5,ELEMENT:6},i=t=>(...e)=>({_$litDirective$:t,values:e});class o{constructor(t){}get _$AU(){return this._$AM._$AU}_$AT(t,e,n){this._$Ct=t,this._$AM=e,this._$Ci=n}_$AS(t,e){return this.update(t,e)}update(t,e){return this.render(...e)}}},7899:(t,e,n)=>{const r=n(2726);e.render=function(t,e,n){let i=n,o=e;void 0!==i||e&&e.getContext||(i=e,e=void 0),e||(o=function(){try{return document.createElement("canvas")}catch(t){throw new Error("You need to specify a canvas element")}}()),i=r.getOptions(i);const a=r.getImageWidth(t.modules.size,i),s=o.getContext("2d"),c=s.createImageData(a,a);return r.qrToImageData(c.data,t,i),function(t,e,n){t.clearRect(0,0,e.width,e.height),e.style||(e.style={}),e.height=n,e.width=n,e.style.height=n+"px",e.style.width=n+"px"}(s,o,a),s.putImageData(c,0,0),o},e.renderToDataURL=function(t,n,r){let i=r;void 0!==i||n&&n.getContext||(i=n,n=void 0),i||(i={});const o=e.render(t,n,i),a=i.type||"image/png",s=i.rendererOpts||{};return o.toDataURL(a,s.quality)}},8409:(t,e,n)=>{"use strict";var r=n(2618),i=n(5707),o=n(3720),a=n(6109),s=n(3494);const c=r.AH`
  :host {
    display: inline-flex !important;
  }

  slot {
    width: 100%;
    display: inline-block;
    font-style: normal;
    font-family: var(--wui-font-family);
    font-feature-settings:
      'tnum' on,
      'lnum' on,
      'case' on;
    line-height: 130%;
    font-weight: var(--wui-font-weight-regular);
    overflow: inherit;
    text-overflow: inherit;
    text-align: var(--local-align);
    color: var(--local-color);
  }

  .wui-line-clamp-1 {
    overflow: hidden;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 1;
  }

  .wui-line-clamp-2 {
    overflow: hidden;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 2;
  }

  .wui-font-medium-400 {
    font-size: var(--wui-font-size-medium);
    font-weight: var(--wui-font-weight-light);
    letter-spacing: var(--wui-letter-spacing-medium);
  }

  .wui-font-medium-600 {
    font-size: var(--wui-font-size-medium);
    letter-spacing: var(--wui-letter-spacing-medium);
  }

  .wui-font-title-600 {
    font-size: var(--wui-font-size-title);
    letter-spacing: var(--wui-letter-spacing-title);
  }

  .wui-font-title-6-600 {
    font-size: var(--wui-font-size-title-6);
    letter-spacing: var(--wui-letter-spacing-title-6);
  }

  .wui-font-mini-700 {
    font-size: var(--wui-font-size-mini);
    letter-spacing: var(--wui-letter-spacing-mini);
    text-transform: uppercase;
  }

  .wui-font-large-500,
  .wui-font-large-600,
  .wui-font-large-700 {
    font-size: var(--wui-font-size-large);
    letter-spacing: var(--wui-letter-spacing-large);
  }

  .wui-font-2xl-500,
  .wui-font-2xl-600,
  .wui-font-2xl-700 {
    font-size: var(--wui-font-size-2xl);
    letter-spacing: var(--wui-letter-spacing-2xl);
  }

  .wui-font-paragraph-400,
  .wui-font-paragraph-500,
  .wui-font-paragraph-600,
  .wui-font-paragraph-700 {
    font-size: var(--wui-font-size-paragraph);
    letter-spacing: var(--wui-letter-spacing-paragraph);
  }

  .wui-font-small-400,
  .wui-font-small-500,
  .wui-font-small-600 {
    font-size: var(--wui-font-size-small);
    letter-spacing: var(--wui-letter-spacing-small);
  }

  .wui-font-tiny-400,
  .wui-font-tiny-500,
  .wui-font-tiny-600 {
    font-size: var(--wui-font-size-tiny);
    letter-spacing: var(--wui-letter-spacing-tiny);
  }

  .wui-font-micro-700,
  .wui-font-micro-600 {
    font-size: var(--wui-font-size-micro);
    letter-spacing: var(--wui-letter-spacing-micro);
    text-transform: uppercase;
  }

  .wui-font-tiny-400,
  .wui-font-small-400,
  .wui-font-medium-400,
  .wui-font-paragraph-400 {
    font-weight: var(--wui-font-weight-light);
  }

  .wui-font-large-700,
  .wui-font-paragraph-700,
  .wui-font-micro-700,
  .wui-font-mini-700 {
    font-weight: var(--wui-font-weight-bold);
  }

  .wui-font-medium-600,
  .wui-font-medium-title-600,
  .wui-font-title-6-600,
  .wui-font-large-600,
  .wui-font-paragraph-600,
  .wui-font-small-600,
  .wui-font-tiny-600,
  .wui-font-micro-600 {
    font-weight: var(--wui-font-weight-medium);
  }

  :host([disabled]) {
    opacity: 0.4;
  }
`;var l=function(t,e,n,r){var i,o=arguments.length,a=o<3?e:null===r?r=Object.getOwnPropertyDescriptor(e,n):r;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)a=Reflect.decorate(t,e,n,r);else for(var s=t.length-1;s>=0;s--)(i=t[s])&&(a=(o<3?i(a):o>3?i(e,n,a):i(e,n))||a);return o>3&&a&&Object.defineProperty(e,n,a),a};let u=class extends r.WF{constructor(){super(...arguments),this.variant="paragraph-500",this.color="fg-300",this.align="left",this.lineClamp=void 0}render(){const t={[`wui-font-${this.variant}`]:!0,[`wui-color-${this.color}`]:!0,[`wui-line-clamp-${this.lineClamp}`]:!!this.lineClamp};return this.style.cssText=`\n      --local-align: ${this.align};\n      --local-color: var(--wui-color-${this.color});\n    `,r.qy`<slot class=${(0,o.H)(t)}></slot>`}};u.styles=[a.W5,c],l([(0,i.MZ)()],u.prototype,"variant",void 0),l([(0,i.MZ)()],u.prototype,"color",void 0),l([(0,i.MZ)()],u.prototype,"align",void 0),l([(0,i.MZ)()],u.prototype,"lineClamp",void 0),u=l([(0,s.E)("wui-text")],u)},8504:(t,e,n)=>{"use strict";n.d(e,{Rt:()=>a,sO:()=>o});var r=n(6752);const{I:i}=r.ge,o=t=>null===t||"object"!=typeof t&&"function"!=typeof t,a=t=>void 0===t.strings},8820:t=>{function e(t){if(!t||t<1)throw new Error("BitMatrix size must be defined and greater than 0");this.size=t,this.data=new Uint8Array(t*t),this.reservedBit=new Uint8Array(t*t)}e.prototype.set=function(t,e,n,r){const i=t*this.size+e;this.data[i]=n,r&&(this.reservedBit[i]=!0)},e.prototype.get=function(t,e){return this.data[t*this.size+e]},e.prototype.xor=function(t,e,n){this.data[t*this.size+e]^=n},e.prototype.isReserved=function(t,e){return this.reservedBit[t*this.size+e]},t.exports=e},9049:t=>{"use strict";t.exports=function(t){for(var e=[],n=t.length,r=0;r<n;r++){var i=t.charCodeAt(r);if(i>=55296&&i<=56319&&n>r+1){var o=t.charCodeAt(r+1);o>=56320&&o<=57343&&(i=1024*(i-55296)+o-56320+65536,r+=1)}i<128?e.push(i):i<2048?(e.push(i>>6|192),e.push(63&i|128)):i<55296||i>=57344&&i<65536?(e.push(i>>12|224),e.push(i>>6&63|128),e.push(63&i|128)):i>=65536&&i<=1114111?(e.push(i>>18|240),e.push(i>>12&63|128),e.push(i>>6&63|128),e.push(63&i|128)):e.push(239,191,189)}return new Uint8Array(e).buffer}},9567:(t,e,n)=>{"use strict";n.r(e),n.d(e,{W3mWalletReceiveView:()=>C});var r=n(2618),i=n(5707),o=n(31),a=n(3450),s=n(6056),c=n(1871),l=n(7601),u=n(8996),h=n(8508),d=n(6742),g=n(148),f=(n(5999),n(2132),n(6887),n(8409),n(9807),n(6109)),p=n(3494);const w=r.AH`
  button {
    display: flex;
    gap: var(--wui-spacing-xl);
    width: 100%;
    background-color: var(--wui-color-gray-glass-002);
    border-radius: var(--wui-border-radius-xxs);
    padding: var(--wui-spacing-m) var(--wui-spacing-s);
  }

  wui-text {
    width: 100%;
  }

  wui-flex {
    width: auto;
  }

  .network-icon {
    width: var(--wui-spacing-2l);
    height: var(--wui-spacing-2l);
    border-radius: calc(var(--wui-spacing-2l) / 2);
    overflow: hidden;
    box-shadow:
      0 0 0 3px var(--wui-color-gray-glass-002),
      0 0 0 3px var(--wui-color-modal-bg);
  }
`;var v=function(t,e,n,r){var i,o=arguments.length,a=o<3?e:null===r?r=Object.getOwnPropertyDescriptor(e,n):r;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)a=Reflect.decorate(t,e,n,r);else for(var s=t.length-1;s>=0;s--)(i=t[s])&&(a=(o<3?i(a):o>3?i(e,n,a):i(e,n))||a);return o>3&&a&&Object.defineProperty(e,n,a),a};let y=class extends r.WF{constructor(){super(...arguments),this.networkImages=[""],this.text=""}render(){return r.qy`
      <button>
        <wui-text variant="small-400" color="fg-200">${this.text}</wui-text>
        <wui-flex gap="3xs" alignItems="center">
          ${this.networksTemplate()}
          <wui-icon name="chevronRight" size="sm" color="fg-200"></wui-icon>
        </wui-flex>
      </button>
    `}networksTemplate(){const t=this.networkImages.slice(0,5);return r.qy` <wui-flex class="networks">
      ${t?.map((t=>r.qy` <wui-flex class="network-icon"> <wui-image src=${t}></wui-image> </wui-flex>`))}
    </wui-flex>`}};y.styles=[f.W5,f.fD,w],v([(0,i.MZ)({type:Array})],y.prototype,"networkImages",void 0),v([(0,i.MZ)()],y.prototype,"text",void 0),y=v([(0,p.E)("wui-compatible-network")],y),n(310),n(6283),n(5090);var m=n(152);const b=r.AH`
  wui-compatible-network {
    margin-top: var(--wui-spacing-l);
  }
`;var x=function(t,e,n,r){var i,o=arguments.length,a=o<3?e:null===r?r=Object.getOwnPropertyDescriptor(e,n):r;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)a=Reflect.decorate(t,e,n,r);else for(var s=t.length-1;s>=0;s--)(i=t[s])&&(a=(o<3?i(a):o>3?i(e,n,a):i(e,n))||a);return o>3&&a&&Object.defineProperty(e,n,a),a};let C=class extends r.WF{constructor(){super(),this.unsubscribe=[],this.address=a.U.state.address,this.profileName=a.U.state.profileName,this.network=s.W.state.activeCaipNetwork,this.preferredAccountType=a.U.state.preferredAccountType,this.unsubscribe.push(a.U.subscribe((t=>{t.address?(this.address=t.address,this.profileName=t.profileName,this.preferredAccountType=t.preferredAccountType):c.P.showError("Account not found")})),s.W.subscribeKey("activeCaipNetwork",(t=>{t?.id&&(this.network=t)})))}disconnectedCallback(){this.unsubscribe.forEach((t=>t()))}render(){if(!this.address)throw new Error("w3m-wallet-receive-view: No account provided");const t=l.$.getNetworkImage(this.network);return r.qy` <wui-flex
      flexDirection="column"
      .padding=${["0","l","l","l"]}
      alignItems="center"
    >
      <wui-chip-button
        data-testid="receive-address-copy-button"
        @click=${this.onCopyClick.bind(this)}
        text=${g.Zv.getTruncateString({string:this.profileName||this.address||"",charsStart:this.profileName?18:4,charsEnd:this.profileName?0:4,truncate:this.profileName?"end":"middle"})}
        icon="copy"
        size="sm"
        imageSrc=${t||""}
        variant="gray"
      ></wui-chip-button>
      <wui-flex
        flexDirection="column"
        .padding=${["l","0","0","0"]}
        alignItems="center"
        gap="s"
      >
        <wui-qr-code
          size=${232}
          theme=${u.W.state.themeMode}
          uri=${this.address}
          ?arenaClear=${!0}
          color=${(0,o.J)(u.W.state.themeVariables["--w3m-qr-color"])}
          data-testid="wui-qr-code"
        ></wui-qr-code>
        <wui-text variant="paragraph-500" color="fg-100" align="center">
          Copy your address or scan this QR code
        </wui-text>
      </wui-flex>
      ${this.networkTemplate()}
    </wui-flex>`}networkTemplate(){const t=s.W.getAllRequestedCaipNetworks(),e=s.W.checkIfSmartAccountEnabled(),n=s.W.state.activeCaipNetwork;if(this.preferredAccountType===m.Vl.ACCOUNT_TYPES.SMART_ACCOUNT&&e)return n?r.qy`<wui-compatible-network
        @click=${this.onReceiveClick.bind(this)}
        text="Only receive assets on this network"
        .networkImages=${[l.$.getNetworkImage(n)??""]}
      ></wui-compatible-network>`:null;const i=t?.filter((t=>t?.assets?.imageId))?.slice(0,5),o=i.map(l.$.getNetworkImage).filter(Boolean);return r.qy`<wui-compatible-network
      @click=${this.onReceiveClick.bind(this)}
      text="Only receive assets on these networks"
      .networkImages=${o}
    ></wui-compatible-network>`}onReceiveClick(){h.I.push("WalletCompatibleNetworks")}onCopyClick(){try{this.address&&(d.w.copyToClopboard(this.address),c.P.showSuccess("Address copied"))}catch{c.P.showError("Failed to copy")}}};C.styles=b,x([(0,i.wk)()],C.prototype,"address",void 0),x([(0,i.wk)()],C.prototype,"profileName",void 0),x([(0,i.wk)()],C.prototype,"network",void 0),x([(0,i.wk)()],C.prototype,"preferredAccountType",void 0),C=x([(0,g.EM)("w3m-wallet-receive-view")],C)},9801:(t,e,n)=>{const r=n(208),i=n(4357),o=n(1433),a=n(5822),s=n(4861),c=n(7044),l=n(6886),u=n(6320);function h(t){return unescape(encodeURIComponent(t)).length}function d(t,e,n){const r=[];let i;for(;null!==(i=t.exec(n));)r.push({data:i[0],index:i.index,mode:e,length:i[0].length});return r}function g(t){const e=d(c.NUMERIC,r.NUMERIC,t),n=d(c.ALPHANUMERIC,r.ALPHANUMERIC,t);let i,o;return l.isKanjiModeEnabled()?(i=d(c.BYTE,r.BYTE,t),o=d(c.KANJI,r.KANJI,t)):(i=d(c.BYTE_KANJI,r.BYTE,t),o=[]),e.concat(n,i,o).sort((function(t,e){return t.index-e.index})).map((function(t){return{data:t.data,mode:t.mode,length:t.length}}))}function f(t,e){switch(e){case r.NUMERIC:return i.getBitsLength(t);case r.ALPHANUMERIC:return o.getBitsLength(t);case r.KANJI:return s.getBitsLength(t);case r.BYTE:return a.getBitsLength(t)}}function p(t,e){let n;const c=r.getBestModeForData(t);if(n=r.from(e,c),n!==r.BYTE&&n.bit<c.bit)throw new Error('"'+t+'" cannot be encoded with mode '+r.toString(n)+".\n Suggested mode is: "+r.toString(c));switch(n!==r.KANJI||l.isKanjiModeEnabled()||(n=r.BYTE),n){case r.NUMERIC:return new i(t);case r.ALPHANUMERIC:return new o(t);case r.KANJI:return new s(t);case r.BYTE:return new a(t)}}e.fromArray=function(t){return t.reduce((function(t,e){return"string"==typeof e?t.push(p(e,null)):e.data&&t.push(p(e.data,e.mode)),t}),[])},e.fromString=function(t,n){const i=function(t){const e=[];for(let n=0;n<t.length;n++){const i=t[n];switch(i.mode){case r.NUMERIC:e.push([i,{data:i.data,mode:r.ALPHANUMERIC,length:i.length},{data:i.data,mode:r.BYTE,length:i.length}]);break;case r.ALPHANUMERIC:e.push([i,{data:i.data,mode:r.BYTE,length:i.length}]);break;case r.KANJI:e.push([i,{data:i.data,mode:r.BYTE,length:h(i.data)}]);break;case r.BYTE:e.push([{data:i.data,mode:r.BYTE,length:h(i.data)}])}}return e}(g(t,l.isKanjiModeEnabled())),o=function(t,e){const n={},i={start:{}};let o=["start"];for(let a=0;a<t.length;a++){const s=t[a],c=[];for(let t=0;t<s.length;t++){const l=s[t],u=""+a+t;c.push(u),n[u]={node:l,lastCount:0},i[u]={};for(let t=0;t<o.length;t++){const a=o[t];n[a]&&n[a].node.mode===l.mode?(i[a][u]=f(n[a].lastCount+l.length,l.mode)-f(n[a].lastCount,l.mode),n[a].lastCount+=l.length):(n[a]&&(n[a].lastCount=l.length),i[a][u]=f(l.length,l.mode)+4+r.getCharCountIndicator(l.mode,e))}}o=c}for(let t=0;t<o.length;t++)i[o[t]].end=0;return{map:i,table:n}}(i,n),a=u.find_path(o.map,"start","end"),s=[];for(let t=1;t<a.length-1;t++)s.push(o.table[a[t]].node);return e.fromArray(s.reduce((function(t,e){const n=t.length-1>=0?t[t.length-1]:null;return n&&n.mode===e.mode?(t[t.length-1].data+=e.data,t):(t.push(e),t)}),[]))},e.rawSplit=function(t){return e.fromArray(g(t,l.isKanjiModeEnabled()))}},9807:(t,e,n)=>{"use strict";var r=n(2618),i=n(5707),o=n(6109),a=n(3612),s=n(3494);const c=r.AH`
  :host {
    display: flex;
    width: inherit;
    height: inherit;
  }
`;var l=function(t,e,n,r){var i,o=arguments.length,a=o<3?e:null===r?r=Object.getOwnPropertyDescriptor(e,n):r;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)a=Reflect.decorate(t,e,n,r);else for(var s=t.length-1;s>=0;s--)(i=t[s])&&(a=(o<3?i(a):o>3?i(e,n,a):i(e,n))||a);return o>3&&a&&Object.defineProperty(e,n,a),a};let u=class extends r.WF{render(){return this.style.cssText=`\n      flex-direction: ${this.flexDirection};\n      flex-wrap: ${this.flexWrap};\n      flex-basis: ${this.flexBasis};\n      flex-grow: ${this.flexGrow};\n      flex-shrink: ${this.flexShrink};\n      align-items: ${this.alignItems};\n      justify-content: ${this.justifyContent};\n      column-gap: ${this.columnGap&&`var(--wui-spacing-${this.columnGap})`};\n      row-gap: ${this.rowGap&&`var(--wui-spacing-${this.rowGap})`};\n      gap: ${this.gap&&`var(--wui-spacing-${this.gap})`};\n      padding-top: ${this.padding&&a.Z.getSpacingStyles(this.padding,0)};\n      padding-right: ${this.padding&&a.Z.getSpacingStyles(this.padding,1)};\n      padding-bottom: ${this.padding&&a.Z.getSpacingStyles(this.padding,2)};\n      padding-left: ${this.padding&&a.Z.getSpacingStyles(this.padding,3)};\n      margin-top: ${this.margin&&a.Z.getSpacingStyles(this.margin,0)};\n      margin-right: ${this.margin&&a.Z.getSpacingStyles(this.margin,1)};\n      margin-bottom: ${this.margin&&a.Z.getSpacingStyles(this.margin,2)};\n      margin-left: ${this.margin&&a.Z.getSpacingStyles(this.margin,3)};\n    `,r.qy`<slot></slot>`}};u.styles=[o.W5,c],l([(0,i.MZ)()],u.prototype,"flexDirection",void 0),l([(0,i.MZ)()],u.prototype,"flexWrap",void 0),l([(0,i.MZ)()],u.prototype,"flexBasis",void 0),l([(0,i.MZ)()],u.prototype,"flexGrow",void 0),l([(0,i.MZ)()],u.prototype,"flexShrink",void 0),l([(0,i.MZ)()],u.prototype,"alignItems",void 0),l([(0,i.MZ)()],u.prototype,"justifyContent",void 0),l([(0,i.MZ)()],u.prototype,"columnGap",void 0),l([(0,i.MZ)()],u.prototype,"rowGap",void 0),l([(0,i.MZ)()],u.prototype,"gap",void 0),l([(0,i.MZ)()],u.prototype,"padding",void 0),l([(0,i.MZ)()],u.prototype,"margin",void 0),u=l([(0,s.E)("wui-flex")],u)},9899:t=>{function e(){this.buffer=[],this.length=0}e.prototype={get:function(t){const e=Math.floor(t/8);return 1==(this.buffer[e]>>>7-t%8&1)},put:function(t,e){for(let n=0;n<e;n++)this.putBit(1==(t>>>e-n-1&1))},getLengthInBits:function(){return this.length},putBit:function(t){const e=Math.floor(this.length/8);this.buffer.length<=e&&this.buffer.push(0),t&&(this.buffer[e]|=128>>>this.length%8),this.length++}},t.exports=e},9953:(t,e)=>{e.L={bit:1},e.M={bit:0},e.Q={bit:3},e.H={bit:2},e.isValid=function(t){return t&&void 0!==t.bit&&t.bit>=0&&t.bit<4},e.from=function(t,n){if(e.isValid(t))return t;try{return function(t){if("string"!=typeof t)throw new Error("Param is not a string");switch(t.toLowerCase()){case"l":case"low":return e.L;case"m":case"medium":return e.M;case"q":case"quartile":return e.Q;case"h":case"high":return e.H;default:throw new Error("Unknown EC Level: "+t)}}(t)}catch(t){return n}}}}]);