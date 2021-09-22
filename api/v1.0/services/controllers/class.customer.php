<?php
	class customers extends restApi{

		public function getCustomerAll($data){


			extract($data);
	        $qry_limit_data  = $this->qryData($limit,$order_by_type,$offset);
	        extract($qry_limit_data);
	        $search_qry = "";
	        if($search_text != ""){
	            $search_qry = " and (customer.customer_name like '%".$search_text."%' or customer.phone_number like '%".$search_text."%' or customer.customer_email like '%".$search_text."%' or customer.queue_name like '%".$search_text."%' or customer.user_name like '%".$search_text."%' or status.status_desc like '%".$search_text."%')";
	        }

			$qry = "select customer.customer_id,customer.customer_name,customer.phone_number,customer.customer_email, customer.customer_type, customer.customer_cat, customer.status, status.status_desc, queue.queue_name, user.user_name from customer inner join customer_type  on customer_type.cust_type_id =customer.customer_type inner join status on status.status_id = customer.status left join queue on queue.queue_id = customer.queue_id left join user on user.user_id = customer.assigned_user where customer.customer_id != '0' ".$search_qry; 
		

			$detail_qry = $qry." order by ".$order_by_name." ".$order_by_type." limit ".$limit." Offset ".$offset;
	        $parms = array();
	        $result["list_data"] = $this->dataFetchAll($detail_qry,array());
	        $result["list_info"]["total"] = $this->dataRowCount($qry,$parms);
	        $result["list_info"]["offset"] = $offset;
			
			return $result;
		}
		
		public function getPDCustomerList($data){

			extract($data);
	        $qry_limit_data  = $this->qryData($limit,$order_by_type,$offset);
	        extract($qry_limit_data);
	        $search_qry = "";
	        if($search_text != ""){
	            $search_qry = " and (customer.customer_name like '%".$search_text."%' or customer.phone_number like '%".$search_text."%' or customer.customer_email like '%".$search_text."%' or customer.queue_name like '%".$search_text."%' or customer.user_name like '%".$search_text."%' or status.status_desc like '%".$search_text."%')";
	        }

	        $qry = "select customer.customer_id,customer.customer_name,customer.phone_number,customer.customer_email, customer.customer_type, customer.customer_cat, customer.status, status.status_desc, queue.queue_name, user.user_name from customer inner join customer_type  on customer_type.cust_type_id =customer.customer_type inner join status on status.status_id = customer.status left join queue on queue.queue_id = customer.queue_id left join user on user.user_id = customer.assigned_user where customer.customer_id != '0' and customer.customer_type = 3 ".$search_qry; 

			$detail_qry = $qry." order by ".$order_by_name." ".$order_by_type." limit ".$limit." Offset ".$offset;
	        $parms = array();
	        $result["list_data"] = $this->dataFetchAll($detail_qry,array());
	        $result["list_info"]["total"] = $this->dataRowCount($qry,$parms);
	        $result["list_info"]["offset"] = $offset;
			
			return $result;
		
		
		}

		public function getCustomer($id){

			$qry = "select customer.customer_id,customer.customer_name,customer.phone_number,customer.customer_email, customer.customer_type, customer.customer_cat, customer.status, status.status_desc, queue.queue_name, user.user_name from customer inner join customer_type  on customer_type.cust_type_id =customer.customer_type inner join status on status.status_id = customer.status left join queue on queue.queue_id = customer.queue_id left join user on user.user_id = customer.assigned_user where customer.customer_id=:customer_id";


			return $this->fetchData($qry, array("customer_id"=>$id));

		}
		
		public function createCustomer($data){
			
            $qry = $this->generateCreateQry($data, "customer");
             $insert_data = $this->db_insert($qry, $data);
            
            if($insert_data != 0){
                
                $result = $this->getCustomer($insert_data);
                
            }
            else{
                
                $result = 0;
            }

            
            return $result;
			
		
		}
		
		
		public function updateCustomer($data, $where_arr){
			
            $qry = $this->generateUpdateQry($data, "customer");
			
			$qry .= "customer_id=:customer_id";
			
			$params = array_merge($data,$where_arr);
			
            $update_data = $this->db_query($qry, $params);
            
            if($update_data != 0){
                
                $result = $this->getCustomer($where_arr["customer_id"]);
                
            }
            else{
                
                $result = 0;
            }
            
            return $result;
			
		}


		
		private function deleteCustomer($id){

			$qry = "Delete from customer where customer_id=:customer_id";
			$qry_result = $this->db_query($qry, array("customer_id"=>$id));
				
			$result = $qry_result == 1 ? 1 : 0;

			return $result;

		}
		

	}