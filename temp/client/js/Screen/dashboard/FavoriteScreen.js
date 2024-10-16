import { fetchApi } from "../../api";
import { Grid } from "../../components/_Grid";
import { Authorization, loading, select2, toast } from "../../util/util"; 
import { productActionBtns } from "../../util/util_product";

export const FavoriteScreen = {
    render(){
        select2();
        const grid = new Grid({
            filters:{
                favorite:true
            },
            afterRender:function(data){  
                var _els = document.querySelectorAll('.btn-remove-favorite');
                for (var index = 0; index < _els.length; index++) {
                    _els[index].addEventListener('click',function (e) {
                        e.preventDefault();
                        const _id = this.getAttribute('data-value'); 
                        Authorization().then(res => {
                            if (res.s) {
                                loading(e, 1) 
                                fetchApi('member/remove_favorite', 'DELETE',{ params:{id:_id}}).then((res) => { 
                                    if ('status' in res ) {
                                        if (res.status) {
                                            document.querySelector('.favorite-item-'+_id).remove()
                                        } else {
                                            throw new Error('');
                                        }
                                    }
                                    loading(e, 0);
                                }).catch(err => { 
                                    toast('e');
                                    loading(e, 0)
                                })
                            } else {
                                toast('n');
                            }
                        }); 
                    });
                }
            }
        });
        grid.render();
        $('.select-2').on('change',function (e) {
            grid.changeFilters({cid:this.value});
        });
    }
};