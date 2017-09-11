<?php

namespace App\Presenters;

use Nette;
use App\Model;


class UserPresenter extends BasePresenter
{

    /** @var Nette\Database\Context */
    private $database;
    
    public function __construct(Nette\Database\Context $database)
    {
        parent::__construct();
        $this->database = $database;

    }    
    
	public function renderDefault($productId)
	{
                
            $this->template->productId = $productId;
            $product = $this->database->table('products')->where('id = ?', $productId)->fetch();
            $this->template->product=$product;
            
            $users = $this->database->table('users')->order('name');
            $this->template->users=$users;                
	}
        
	public function renderHistory($userId)
	{
            
            $this->template->username = $this->database->table('users')->where('id = ?', $userId)->fetch()->name;
                
            $history = $this->database->table('history')->select('user,productName,timestamp,price,user.name')->where('user = ?', $userId)->order('timestamp DESC');
            
            $this->template->priceSum=$history->sum('price');
            $this->template->history=$history;                 
	}        

}
