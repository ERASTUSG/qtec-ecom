$("#refresh-rates").on("click",r=>{$.ajax({type:"GET",url:route("admin.currency_rates.refresh"),success(){DataTable.reload("#currency-rates-table .table"),window.admin.stopButtonLoading($(r.currentTarget))},error(e){error(e.responseJSON.message),window.admin.stopButtonLoading($(r.currentTarget))}})});
//# sourceMappingURL=main-9dfef545-v4.0.0.js.map
