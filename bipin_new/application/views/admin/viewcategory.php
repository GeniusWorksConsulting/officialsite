<style>
#mg-multisidetabs .list-group-item:first-child {
        border-top-left-radius: 0;
        border-top-right-radius: 0;
    }
    #mg-multisidetabs .list-group-item:last-child {
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
    }
    #mg-multisidetabs .list-group{
      margin-bottom:0;
    }
    .slide-container{
      overflow:hidden;
    }
    #mg-multisidetabs .list-sub{
      display:none;
    }
    #mg-multisidetabs .panel{
      margin-bottom:0;
    }
    #mg-multisidetabs .panel-body{
      padding:1px 2px;
    }
    .mg-icon{
      font-size:10px;
      line-height: 20px;
    }
</style>
<div class="content-page">
    <!-- Start content -->
    <div class="content">

<div class="container">
   <div class="row">
      <div class="col-sm-12">
         <div class="col-sm-12">
            <div class="content">
               <div class="container">
                  <!-- Page-Title -->                        
                  <div class="row">
                     <div class="col-sm-12">
                        <h4 class="page-title">Category</h4>
                        <p class="text-muted page-title-alt"></p>
                     </div>
                  </div>
				   <?php if($this->session->flashdata('message_success') != '') {?>				
               <div class="alert alert-success alert-dismissable" style="clear:both;">					<a href="#" class="close" data-dismiss="alert" aria-label="close">�</a>					<strong><?php echo $this->session->flashdata('message_success');?></strong>				</div>
               <?php }?>
				  
					<table class="table table-striped">
    <thead>
      <tr>
        <th>Index</th>
        <th>Name</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
		<?php for($i=0;$i<sizeof($category);$i++){
			?>
			<tr>
				<td><?php echo $i+1; ?></td>
				<td><?php echo $category[$i]['name']; ?></td>
				<td>
					<a href="<?php echo base_url(); ?>admin/addcategory?id=<?php echo $category[$i]['id']; ?>" class="btn btn-primary">Edit</a>
					<a href="<?php echo base_url(); ?>admin/deletecategory?id=<?php echo $category[$i]['id']; ?>" class="btn btn-danger">Delete</a>
				</td>
			  </tr>
			<?php
		}?>
      
    </tbody>
  </table>
				
                                    
               </div>
               <!-- container -->                
            </div>
         </div>
      </div>
   </div>
</div>
   </div>
</div>