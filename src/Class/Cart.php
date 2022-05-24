<?php

namespace App\Class;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Cart
{
    private $session;
    private $entityManagerInterface;

    public function  __construct(EntityManagerInterface $entityManagerInterface, RequestStack $stack)
    {
        $this->session = $stack->getSession();
        $this->entityManagerInterface = $entityManagerInterface;
    }

    public function add($id)  // Je stock la session actuelle du panier dans la variable cart qui renvoie un tableau 
    {
        $cart = $this->session->get('cart', []); 
         // si le panier a bien un produit inserer id specifique au produit

        if (!empty($cart[$id])) {  //Alors je rajoute une quantity
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }

        $this->session->set('cart', $cart);
    }


    public function get()
    {
    
        return $this->session->get('cart');
    }

    public function remove()
    {
        return $this->session->remove('cart');
    }


    public function delete($id)
    {
        $cart = $this->session->get('cart', []); 

        unset($cart[$id]);

        return $this->session->set('cart', $cart);
    }

    public function decrease($id)
    {
        $cart = $this->session->get('cart', []); 

        if ($cart[$id] > 1) {
            $cart[$id]--;
            // retirer une quantité
        } else {
            unset($cart[$id]);
        }

        return $this->session->set('cart', $cart);
    }


    public function getFull()
    {
        
        $cartFull = [];

        if ($this->get()) {
                foreach ($this->get() as $id => $quantity) {
                    $product_object = $this->entityManager->getRepository( Product::class)->findOneBy(['id'=>$id]);
                    if(!$product_object) {
                        $this->delete($id);
                        continue;
                    }
                    $cartFull[] = [
                    'product' => $product_object,
                    'quantity' => $quantity
                ];
            }
        }

        return $cartFull;
    }
}

