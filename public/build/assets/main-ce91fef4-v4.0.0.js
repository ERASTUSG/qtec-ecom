class l{constructor(t,e={}){this.rateId=t,this.rate=e}html(){return this.html=this.template({rateId:this.rateId,rate:this.rate}),this.eventListeners(),this.html}updateState(){this.html.find(".country select").trigger("change")}template(t){let e=_.template($("#tax-rate-template").html());return $(e(t))}eventListeners(t){this.html.find(".country select").on("change",e=>{e.currentTarget.value&&this.changeState(e.currentTarget.value)}),this.html.on("click",".delete-row",this.delete)}changeState(t){this.getStateField().prop("disabled",!0);let e=this.getStateField().val();$.getJSON(route("countries.states.index",t),a=>{this.getStateField().replaceWith(this.getStateTemplate(a)).prop("disabled",!1),e&&this.getStateField().val(e)})}getStateField(){let t=$.escapeSelector(`rates.${this.rateId}.state`);return $(`#${t}`)}getStateTemplate(t){return $.isEmptyObject(t)?this.getInputStateTemplate():this.getSelectStateTemplate(t)}getInputStateTemplate(){return _.template($("#state-input-template").html())({rateId:this.rateId})}getSelectStateTemplate(t){return _.template($("#state-select-template").html())({rateId:this.rateId,states:t})}delete(t){$(t.currentTarget).closest(".tax-rate").remove()}}class i{constructor(){this.rateCount=0,this.addTaxRates(FleetCart.data.tax_rates),this.rateCount===0&&this.addTaxRate(),this.addTaxRatesErrors(FleetCart.errors.tax_rates),this.eventListeners(),this.sortable()}addTaxRates(t){for(let e of t)this.addTaxRate(e)}addTaxRate(t={}){let e=new l(this.rateCount++,t);$("#tax-rates").append(e.html()),e.updateState(),window.admin.tooltip()}addTaxRatesErrors(t){for(let e in t){let a=$.escapeSelector(e),r=$(`#${a}`).parent();r.addClass("has-error"),r.append(`<span class="help-block">${t[e][0]}</span>`)}}eventListeners(){$("#add-new-rate").on("click",()=>this.addTaxRate())}sortable(){Sortable.create(document.getElementById("tax-rates"),{handle:".drag-handle",animation:150})}}window.admin.removeSubmitButtonOffsetOn("#rates");new i;
//# sourceMappingURL=main-ce91fef4-v4.0.0.js.map
