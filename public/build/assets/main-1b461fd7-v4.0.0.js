$("form").on("submit",e=>{$(e.currentTarget).find(":input").filter((t,r)=>!r.value).attr("disabled","disabled")});$("#report-type").on("change",e=>{window.location=route("admin.reports.index",{type:e.currentTarget.value})});
//# sourceMappingURL=main-1b461fd7-v4.0.0.js.map
