<?php 
	namespace ecommarg\cart;
	
	use ecommarg\cart\SaveAdapterInterface as SaveAdapter;
	use ecommarg\cart\ProductInterface as Product;
	use ecommarg\cart\FileAdapter as File;
		
	Class Cart implements CartInterface{
			
		private	$adapter;

		public function __construct (SaveAdapter $adapter){
			$this->adapter = $adapter;
		}

		public function add(Product $producto, $quantity=1){
			$quantity=(int) $quantity;

			if($quantity<=0){
				throw new \Exception("Cantidad invalida");
				
			}

			$this->adapter->set($producto->getId(),json_encode([
																'quantity'=>$quantity,
																'product'=>$producto
			]));
		}

		public function get($id){
			return $this->adapter->get($id);
		}

		public function getAll(){
			$data=$this->adapter->getAll();

			foreach ($data as &$item) {
				$item = json_decode($item);
			}
			return $data;
		}

		public function replace($array){
			return $this->adapter->replace($array);
		}
	}