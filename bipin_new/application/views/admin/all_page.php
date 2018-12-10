<?php $this->load->view('header'); ?> 
	
<div class="container" style="margin-left:15%;">
	<div class="row">
		<div class="col-sm-10" style="margin-top: 5%;">
			<table class="table table-bordered">			
				<thead>				
					<th>Title</th>				
					<th>Slug</th>				
					<th>Author Name</th>				
					<th>Language</th>				
					<th>Status</th>				
					<th>Edit</th>
				</thead>			
				<tbody>
					<?php for($i=0;$i<sizeof($pages);$i++){ ?>					
						<tr>						
							<td><?= $pages[$i]['page_title'] ?></td>						
							<td><?= $pages[$i]['slug'] ?></td>						
							<td><?= $pages[$i]['author_id'] ?></td>						
							<td><?= $pages[$i]['language_id'] ?></td>						
							<td><?= $pages[$i]['status_id'] ?></td>						
							<td><a href="<?= base_url()."admin/update/".$pages[$i]['id'] ?>">Edit</a></td>					
						</tr>					
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>