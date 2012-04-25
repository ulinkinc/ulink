<?php echo ($javascript->link(array('jquery-common'))); //includes .JS files?>

<div id="ajax_msg"></div>

<table border="0" cellspacing="0" cellpadding="0" class="listing edit" >
			<tr class="ListHeading">
				<td width="10%" align="center">Sr. No.</th>
				<td width="20%" align="center">Firstname
					<span id="pagination">
					<?php
					
					$arrURL = explode("direction:", $_REQUEST['url']);
                                        
					$arrSort = explode("sort:", $_REQUEST['url']);
					if( $arrSort[1] != "" )
					{
						$arrSortSub = explode("/", $arrSort[1]);
						$arrSortSub = $arrSortSub[0];
					}
					
					if( $arrSortSub == "firstname" )
					{
						if( isset($arrURL['1']) )
						{
							if( $arrURL['1'] != "desc" )
								$sortingImage = "<img src='".$html->url('/img/aroow2.gif')."' alt='Desc' title='Desc' />";
								
							else
								$sortingImage = "<img src='".$html->url('/img/aroow1.gif')."' alt='Asc' title='Asc' />";
						}
					}
					else
					{
						$sortingImage = "<img src='".$html->url('/img/aroow1.gif')."' alt='Asc' title='Asc' />";
					}

					?>
					<?php echo $paginator->sort(html_entity_decode($sortingImage), 'firstname');?>
					</span>
				</th>
					<td width="20%" align="center">Last Name
					<span id="pagination">
					<?php
					
					$arrURL = explode("direction:", $_REQUEST['url']);
                                        
					$arrSort = explode("sort:", $_REQUEST['url']);
					if( $arrSort[1] != "" )
					{
						$arrSortSub = explode("/", $arrSort[1]);
						$arrSortSub = $arrSortSub[0];
					}
					
					if( $arrSortSub == "lastname" )
					{
						if( isset($arrURL['1']) )
						{
							if( $arrURL['1'] != "desc" )
								$sortingImage = "<img src='".$html->url('/img/aroow2.gif')."' alt='Desc' title='Desc' />";
								
							else
								$sortingImage = "<img src='".$html->url('/img/aroow1.gif')."' alt='Asc' title='Asc' />";
								
						}
					}
					else
					{
						$sortingImage = "<img src='".$html->url('/img/aroow1.gif')."' alt='Asc' title='Asc' />";
					}

					?>
					<?php echo $paginator->sort(html_entity_decode($sortingImage), 'lastname');?>
					</span>
				</th>
				
				<td width="20%" align="center">Email
					<span id="pagination">
					<?php
					
					$arrURL = explode("direction:", $_REQUEST['url']);
                                        
					$arrSort = explode("sort:", $_REQUEST['url']);
					if( $arrSort[1] != "" )
					{
						$arrSortSub = explode("/", $arrSort[1]);
						$arrSortSub = $arrSortSub[0];
					}
					
					if( $arrSortSub == "email" )
					{
						if( isset($arrURL['1']) )
						{
							if( $arrURL['1'] != "desc" )
								$sortingImage = "<img src='".$html->url('/img/aroow2.gif')."' alt='Desc' title='Desc' />";
								
							else
								$sortingImage = "<img src='".$html->url('/img/aroow1.gif')."' alt='Asc' title='Asc' />";
								
						}
					}
					else
					{
						$sortingImage = "<img src='".$html->url('/img/aroow1.gif')."' alt='Asc' title='Asc' />";
					}

					?>
					<?php echo $paginator->sort(html_entity_decode($sortingImage), 'email');?>
					</span>
				</th>
				
                <td width="20%" align="center">Active/In active
					<span id="pagination">
					<?php
					
					$arrURL = explode("direction:", $_REQUEST['url']);
                                        
					$arrSort = explode("sort:", $_REQUEST['url']);
					if( $arrSort[1] != "" )
					{
						$arrSortSub = explode("/", $arrSort[1]);
						$arrSortSub = $arrSortSub[0];
					}
					
					if( $arrSortSub == "activation" )
					{
						if( isset($arrURL['1']) )
						{
							if( $arrURL['1'] != "desc" )
								$sortingImage = "<img src='".$html->url('/img/aroow2.gif')."' alt='Desc' title='Desc' />";
								
							else
								$sortingImage = "<img src='".$html->url('/img/aroow1.gif')."' alt='Asc' title='Asc' />";
								
						}
					}
					else
					{
						$sortingImage = "<img src='".$html->url('/img/aroow1.gif')."' alt='Asc' title='Asc' />";
					}

					?>
					<?php echo $paginator->sort(html_entity_decode($sortingImage), 'activation');?>
					</span>
				</th>
				
				
                
                
                
				<td width="10%" align="center">Edit</th>
				
				<td width="10%" align="center">Delete</th>
			</tr>
		<?php
		
			$i = 0;
                         
			foreach( $users as $user )
			{ 
				
				if( $page_no )
					$sr_no = ($page_no * $paginate_limit) - $paginate_limit + 1;
				else
					$sr_no = 1;
				
				$class = "";
				if( $i%2 != 0 )
					$class = 'class="gr-row"';
				?>
				<tr <?php e($class); ?> id="<?php echo $user['User']['id'] ?>">
					<td align="center"><?php e($i++ + $sr_no ); ?>. </td>
					<td align="left"><?php e($user['User']['firstname']); ?></td>
					<td align="left">
					<?php e($user['User']['lastname']); ?></td>
					<td align="left">
					<?php e($user['User']['email']); ?></td>
							
					<td><a class="changeStatus" path="<?php e($html->url('/admin/users/user_changeStatus/'.$user['User']['id'])); ?>" id="<?php echo $user['User']['id'];?>"><?php if($user['User']['activation']==0) echo "<img src='".$html->url('/img/unpublish.png')."' border='0'>"; else echo "<img src='".$html->url('/img/publish.png')."' border='0'>";?></a></td>
                    
                    <td align="center"><a href="<?php e($html->url('/admin/users/user_edit/'.$user['User']['id']));?>"><img src="<?php e($html->url('/img/edit-icon.png')); ?>" /></td>
					
					<td><?php echo $html->link('<img src="'.$html->url('/img/delete.gif').'" />',array('action'=>'user_delete',$user['User']['id']),array('class'=>'confirm_delete','id'=>$user['User']['id'],'escape'=>false));?>
				</tr>
				<?php
			}
		?>
		
		</table>


		<?php
		if ( count($users)>0 ) {
		     /* Display paging info */
		?>
<div id="pagination">		
		<div class="bottom-text">		
		<?php
		
		//      echo $paginator->prev();
		//      echo $paginator->numbers(array('separator'=>' - '));
		//      echo $paginator->next();

		      echo $paginator->first("<<", array('class'=>'footer_nav'));
		      echo '&nbsp;&nbsp;';
		      echo $paginator->prev("<", array('class'=>'footer_nav'));
			  echo '&nbsp;&nbsp;'; 
		      echo $paginator->numbers(array('separator'=>' | '));
			  echo '&nbsp;&nbsp;'; 
		      echo $paginator->next(">", array('class'=>'footer_nav'));
		      echo '&nbsp;&nbsp;';
		      echo $paginator->last(">>", array('class'=>'footer_nav'));
		
		?>
		</div>

</div>
		<?php
		}
		?> 

