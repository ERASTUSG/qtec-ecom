$("#order-status").change(r=>{$.ajax({type:"PUT",url:route("admin.orders.status.update",r.currentTarget.dataset.id),data:{status:r.currentTarget.value},success:e=>{success(e)},error:e=>{error(e.responseJSON.message)}})});
//# sourceMappingURL=main-11a43c82-v4.0.0.js.map
