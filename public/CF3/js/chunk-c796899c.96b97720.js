(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-c796899c"],{ab14:function(t,e,s){"use strict";e["a"]={data:()=>({mainId:Math.floor(Math.random()*10**10),selected:0,elements:[],stateStr:"",rendered:!1,firstSelection:!0}),watch:{menuState(){this.domUpdated()}},created(){window.addEventListener("resize",this.domUpdated)},mounted(){this.$nextTick(()=>{this.crearElementos()})},computed:{menuState(){return this.$store.getters.isMenuOpen},navObj(){if(!this.elements.length||!this.secuencial)return{};const t=this.elements.map(t=>t.id),e=t.indexOf(this.selected);if(e<0)return{};const s={};return 0===e?s.next=t[e+1]:(e+1===t.length||(s.next=t[e+1]),s.back=t[e-1]),s}},beforeDestroy(){window.removeEventListener("resize",this.domUpdated)},updated(){this.$nextTick(()=>{this.getStateStr()!=this.stateStr&&this.crearElementos()})},methods:{crearElementos(){return this.$slots.default&&this.$slots.default.length?(this.domUpdated(),this.elements=this.$slots.default.map((t,e)=>{const s=t.data&&t.data.attrs?t.data.attrs:[];return{id:this.mainId+e+1,elm:t.elm,...s}}),this.firstSelection&&(this.selected=this.selected>0?this.selected:this.elements[0].id),void(this.stateStr=this.getStateStr())):[]},getActiveHeight(t){return this.$refs[t][0].scrollHeight+"px"},getStateStr(){return this.$slots.default.map(t=>t.elm.outerHTML).join("")},domUpdated(){this.rendered=!1,setTimeout(()=>{this.rendered=!0},100)}}}},d0b1:function(t,e,s){"use strict";s.r(e);var i=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"tabs-b"},[s("div",{staticClass:"tabs-b__header row m-0"},t._l(t.elements,(function(e,i){return s("div",{key:"tabs-b-menu-"+e.id,staticClass:"col-6 col-sm-4 col-lg tabs-b__tab",class:{"tabs-b__tab--active":t.selected===e.id},on:{click:function(s){t.selected=e.id},mouseover:function(e){t.mostrarIndicador=!(t.mostrarIndicador&&i>=1)&&t.mostrarIndicador}}},[t.mostrarIndicador&&1===i?s("div",{staticClass:"indicador__container"},[s("div",{staticClass:"indicador--click"})]):t._e(),e.icono?s("div",{staticClass:"tabs-b__tab__icon"},[s("img",{attrs:{src:e.icono}})]):t._e(),s("div",{staticClass:"tabs-b__tab__title"},[s("span",{domProps:{innerHTML:t._s(e.titulo)}})])])})),0),t._l(t.elements,(function(e){return s("div",{directives:[{name:"show",rawName:"v-show",value:t.selected===e.id,expression:"selected === elm.id"},{name:"child",rawName:"v-child",value:e.elm,expression:"elm.elm"}],key:"tabs-content-"+e.id,staticClass:"tabs-b__content-item"})})),s("div",{staticClass:"hidden-slot"},[t._t("default")],2)],2)},a=[],n=s("ab14"),d={name:"TabsB",mixins:[n["a"]],data:function(){return{mostrarIndicador:!0}}},r=d,o=s("2877"),c=Object(o["a"])(r,i,a,!1,null,null,null);e["default"]=c.exports}}]);
//# sourceMappingURL=chunk-c796899c.96b97720.js.map