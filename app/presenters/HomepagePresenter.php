<?php

namespace App\Presenters;

use Nette;
use App\Model;
use Nette\Utils\DateTime;


class HomepagePresenter extends BasePresenter
{

    /** @var Nette\Database\Context */
    private $database;
    
    public function __construct(Nette\Database\Context $database)
    {
        parent::__construct();
        $this->database = $database;

    }    
    
	public function renderDefault($user,$productId,$ordered)
	{

            $this->template->ordered=$ordered;
            if ($user && $productId) {
                $product = $this->database->table('products')->where('id = ?', $productId)->fetch();
                
                    $this->database->table('history')->insert(array(
                        'user' => $user,
                        'productId' => $productId,
                        'productName' => $product->name,
                        'price' => $product->price,

                    ));
                    
                    $this->database->table('products')
                            ->where('id = ?', $productId)
                            ->update(array(
                        'stock' =>  new Nette\Database\SqlLiteral('stock-1'),
                    ));
                    
                    //$this->flashMessage('Hotovo, objednávka zapsána.');
                    $this->redirect('Homepage:default', array("ordered" => 'yes'));
            }
            
            
            $history = $this->database->table('history')->select('history.id,user,productName,timestamp,price,user.name')->order('timestamp DESC')->limit('10');
            $this->template->history=$history;  
            $this->template->timestamp = DateTime::from('now')->modify('-60 seconds');
            
            
            $historySort = $this->database->table('history')->select('COUNT(id) AS celkem,productId')->group('productId')->order('celkem');
            $sorted=  array();
            foreach ($historySort as $item) {
                $sorted[]="$item->productId";
            }
            
            $products = $this->database->table('products')->where('stock > ?', '0');
            if ($sorted) {
                $products=$products->order('FIELD(id, ?) DESC', $sorted);
            }
            $this->template->products=$products;     
            
            $this->template->dluznicek = $this->database->table('history')->select('SUM(price) AS sum,user.name,user')->group('user.name')->order('user.name');
            $this->template->dluznicekAll = $this->database->table('history')->select('SUM(price) AS sum')->fetch()->sum;
	}

}
