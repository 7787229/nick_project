(function (window) {

if (!!window.JCCatalogFavorite)
{
	return;
}
window.JCCatalogFavorite = function (arParams)
{
	this.ID = '';

	this.visual = {
		DELAY_ID: ''
	};

	this.PARENT_OBJ = {};

	this.basketParams = {};

	this.errorCode = 0;

	if ('object' === typeof arParams) {
		this.ID = arParams.ID;
		this.visual = arParams.VISUAL;
	}

	if (0 === this.errorCode)
	{
		BX.ready(BX.delegate(this.Init,this));
	}
};

window.JCCatalogFavorite.prototype.Init = function()
{

	if (!!this.visual.DELAY_ID)
	{
		// отложенные
		this.obDelayBtn = BX(this.visual.DELAY_ID);
	}

	if (!!this.obDelayBtn) {

		BX.bind(this.obDelayBtn, 'click', BX.delegate(this.AddDelay, this));
	}

};

window.JCCatalogFavorite.prototype.AddDelay = function()
{
	var ob = 'ob'+this.ID;
	this.PARENT_OBJ = window[ob];
	if ( 'object' === typeof this.PARENT_OBJ ) {

		this.DelayUrl = "/ajax/add2delay.php";

		if (!!this.PARENT_OBJ.basketData.sku_props) {
			this.basketParams[this.PARENT_OBJ.basketData.sku_props_var] = this.PARENT_OBJ.basketData.sku_props;
		}
//		this.PARENT_OBJ.FillBasketProps();
		this.basketParams["id"] = (this.PARENT_OBJ.productType===3 ? this.PARENT_OBJ.offers[this.PARENT_OBJ.offerNum].ID:this.PARENT_OBJ.product.id);
		this.basketParams["arprops"] = this.PARENT_OBJ.ARPROPS;
		this.basketParams["ibl"] = this.PARENT_OBJ.IBL;
		this.basketParams["DELAY_ID"] = this.visual.DELAY_ID;

		this_ = this;

        $.ajax({
            type: "GET",
            url: this.DelayUrl,
            data: this.basketParams,
            success: function(data){
                  if(data === "deleted"){
                      $("#"+this_.visual.DELAY_ID).removeClass('active');
                      $("#"+this_.visual.DELAY_ID+" i.fa").addClass('fa-heart-o');
                      $("#"+this_.visual.DELAY_ID+" i.fa").removeClass('fa-heart');        
                  }else{
                      $("#"+this_.visual.DELAY_ID).addClass('active');
                      $("#"+this_.visual.DELAY_ID+" i.fa").removeClass('fa-heart-o');
                      $("#"+this_.visual.DELAY_ID+" i.fa").addClass('fa-heart');
                  }

                  $.post("/ajax/delay_line.php", function(data) {
                          $(".favorites").html(data);
                  }); 
            }
          });
	}

};

})(window);