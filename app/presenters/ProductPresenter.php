<?php

namespace App\Presenters;

use Nette;
use App\Model;
use Nette\Utils\DateTime;

class ProductPresenter extends BasePresenter
{

    /** @var Nette\Database\Context */
    private $database;
    
    public function __construct(Nette\Database\Context $database)
    {
        parent::__construct();
        $this->database = $database;

    }    

	public function actionDelete($id)
	{
            
            $timestamp = DateTime::from('now')->modify('-75 seconds');
            $history = $this->database->table('history')->where('id = ?', $id);
            
            $historyFetch=$history->fetch();
            
            if ($historyFetch->timestamp>$timestamp) {
                
                    $productId=$historyFetch->productId;
                    $this->database->table('products')
                            ->where('id = ?', $productId)
                            ->update(array(
                        'stock' =>  new Nette\Database\SqlLiteral('stock+1'),
                    ));                
                
                    $history->delete();
                    $this->flashMessage('Objednávka smazána.');
                    $this->redirect('Homepage:');                 
            } else {
                    $this->flashMessage('Objednávku můžeš smazat do 60 vteřin.', $type='error');
                    $this->redirect('Homepage:');                 
            }
         
	}        

}
