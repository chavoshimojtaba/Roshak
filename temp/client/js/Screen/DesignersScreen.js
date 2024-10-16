import { fetchApi } from "../api";
import { Grid } from "../components/_Grid";
import { Authorization, isMobile, loading,  select2, toast } from "../util/util";
import { productActionBtns } from "../util/util_product";
export const DesignersScreen = {
    render(){
        let init = 1;
        const grid = new Grid({
            afterRender:function(data){
                productActionBtns();
                if (!init) {
                    /* document.querySelector('.selector-total').innerText = (data.total>0)?data.total:0;
                    document.querySelector('.selector-q').innerText = data.q; */
                }
                init = 0
            }
        });
        grid.render();
        const sortRadio = document.getElementsByName('inp_sort');
        for (let index = 0; index < sortRadio.length; index++) {
            sortRadio[index].addEventListener('click',function (e) { 
                grid.changeFilters({sort:this.value});
            });
        }
        select2().on('select2:select', function (e) { 
            document.location.href = HOST+'designers/'+this.value+'-ex';
        });

    },
    slug(){ 
        if (document.querySelector('.sort-widget') != null) {
            this.render();
            return;
        }
        select2(); 
        console.log(document.querySelector('#inp_id'));
        const grid = new Grid({
            filters:{
                mid:document.querySelector('#inp_id').value
            },
            afterRender:function(data){
                productActionBtns();
            }
        });
        grid.render();
      /*   var myModal = new bootstrap.Modal(document.getElementById('exampleModal'), {})
        document.querySelector('#exampleModal-btn').addEventListener('click',function (e) { 
            myModal.show()
        }) */
        const catOptions = document.querySelectorAll('#‍inp_cid option');
        const followBtn = document.querySelector('.member-action');
        if (isMobile()) { 
            var myOffcanvas = document.getElementById('offcanvasFilter')
            var bsOffcanvas = new bootstrap.Offcanvas(myOffcanvas);
        }
        $('select').on('select2:select', function (e) {
            const id = e.currentTarget.name;
            if(id == 'filetype'){
                grid.changeFilters({filetype:this.value});
                for (let index = 0; index < catOptions.length; index++) {
                    const element = catOptions[index];
                    if (element.value == '0' || this.value == element.getAttribute('data-type') ) {
                        element.disabled=false
                    }else{
                        element.disabled=true
                    }
                }
                select2('#inp_cid');
            }else{
                const params = {};
                const key = this.name;
                if (this.value && this.value != 'delete') {
                    this.classList.add('active')
                    params[key] = this.value;
                }else{
                    this.classList.remove('active')
                    params[key] = 'delete';
                }
                grid.changeFilters(params);
                
            }
            if (isMobile()) {
                bsOffcanvas.hide();
            }
        });

        followBtn.addEventListener('click',function (e) {
            e.preventDefault();
            Authorization().then(res => {
                if (!res.s ) {
                    toast('n');
                    return;
                }
                const value     = this.getAttribute('data-id');
                loading(e,1)
                fetchApi('member/follow', 'POST',{body:  {id:value}}).then((res) => {
                    if (res.status == 1) {
                        if (followBtn.classList.contains('followed')) {
                            followBtn.innerText = 'دنبال کردن';
                            followBtn.classList.remove('followed') ;
                        }else{
                            followBtn.innerText = 'دنبال شده';
                            followBtn.classList.add('followed') ;
                        }
                    }else if (res.status == -1) {
                        toast('n');

                    } else {
                        throw new Error('');
                    }
                    loading(e,0);
                }).catch(err=>{
                    toast('e');
                    loading(e,0)
                })
            });
        },false);
         
    }

};