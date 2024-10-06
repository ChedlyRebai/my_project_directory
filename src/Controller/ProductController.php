<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository; // Don't forget to include this
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse; // Use JsonResponse for structured responses
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductController extends AbstractController
{
    #[Route('/product', name: 'app_product')]
    public function createProduct(EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        $product = new Product();
        $product->setName('Keyboard');
        $product->setPrice(111);
       
        // Validate the product before persisting
        $errors = $validator->validate($product);
        if (count($errors) > 0) {
            return new Response((string) $errors, Response::HTTP_BAD_REQUEST);
        }

        // Tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($product);

        // Actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Saved new product with id ' . $product->getId());
    }

    #[Route('/product/{id}', name: 'product_show')]
    public function show(ProductRepository $productRepository, int $id): Response
    {
        // Find the product by ID
        $product = $productRepository->find($id);

        // Check if the product exists
        if (!$product) {
            return new Response('Product not found', Response::HTTP_NOT_FOUND);
        }

        // Return a response with the product ID or any other product details
        return new Response('Showing product with id ' . $product->getName());
    }
    #[Route('/products',name:'all_products')]
    public function showAllProducts(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();

        // Check if there are any products
        if (empty($products)) {
            return new Response('No products found', Response::HTTP_NOT_FOUND);
        }

        // Convert products to a string representation (or JSON)
        // Here, we'll use JsonResponse for better structure
        $productData = [];
        foreach ($products as $product) {
            $productData[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'price' => $product->getPrice(),
            ];
        }

        return new JsonResponse($productData); // Using JsonResponse for structured output
    }

    #[Route('/product/update/{id}', name: 'product_update')]
    public function updateProduct(EntityManagerInterface $entityManager, ProductRepository $productRepository, ValidatorInterface $validator, int $id): Response
    {
        // Find the product by ID
        $product = $productRepository->find($id);

        // Check if the product exists
        if (!$product) {
            return new Response('Product not found', Response::HTTP_NOT_FOUND);
        }

        // Update the product details
        $product->setName('Updated Keyboard');
        $product->setPrice(222);

        // Validate the updated product before persisting
        $errors = $validator->validate($product);
        if (count($errors) > 0) {
            return new Response((string) $errors, Response::HTTP_BAD_REQUEST);
        }

        // Tell Doctrine you want to (eventually) save the updated Product (no queries yet)
        $entityManager->persist($product);

        // Actually executes the queries (i.e. the UPDATE query)
        $entityManager->flush();

        return new Response('Updated product with id ' . $product->getId());
    }

    #[Route('/product/delete/{id}', name: 'product_delete')]
    public function deleteProduct(EntityManagerInterface $entityManager, ProductRepository $productRepository, int $id): Response
    {
        // Find the product by ID
        $product = $productRepository->find($id);

        // Check if the product exists
        if (!$product) {
            return new Response('Product not found', Response::HTTP_NOT_FOUND);
        }

        // Tell Doctrine you want to (eventually) remove the Product (no queries yet)
        $entityManager->remove($product);

        // Actually executes the queries (i.e. the DELETE query)
        $entityManager->flush();

        return new Response('Deleted product with id ' . $id);
    }

    #[Route('/product/delete-all', name: 'product_delete_all')]
    public function deleteAllProducts(EntityManagerInterface $entityManager, ProductRepository $productRepository): Response
    {
        // Get all products
        $products = $productRepository->findAll();

        // Check if there are any products
        if (empty($products)) {
            return new Response('No products found', Response::HTTP_NOT_FOUND);
        }

        // Remove all products
        foreach ($products as $product) {
            $entityManager->remove($product);
        }

        // Actually executes the queries (i.e. the DELETE queries)
        $entityManager->flush();

        return new Response('Deleted all products');
    }

    #[Route('/product/search/{name}', name: 'product_search')]
    public function searchProduct(ProductRepository $productRepository, string $name): Response
    {
        // Find products by name
        $products = $productRepository->findByName($name);

        // Check if there are any products
        if (empty($products)) {
            return new Response('No products found', Response::HTTP_NOT_FOUND);
        }

        // Convert products to a string representation (or JSON)
        // Here, we'll use JsonResponse for better structure
        $productData = [];
        foreach ($products as $product) {
            $productData[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'price' => $product->getPrice(),
            ];
        }

        return new JsonResponse($productData); // Using JsonResponse for structured output
    }

    #[Route('/product/search-price/{price}', name: 'product_search_price')]
    public function searchProductByPrice(ProductRepository $productRepository, int $price): Response
    {
        // Find products by price
        $products = $productRepository->findByPrice($price);

        // Check if there are any products
        if (empty($products)) {
            return new Response('No products found', Response::HTTP_NOT_FOUND);
        }

        // Convert products to a string representation (or JSON)
        // Here, we'll use JsonResponse for better structure
        $productData = [];
        foreach ($products as $product) {
            $productData[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'price' => $product->getPrice(),
            ];
        }

        return new JsonResponse($productData); // Using JsonResponse for structured output
    }

    #[Route('/product/search-price-range/{min}/{max}', name: 'product_search_price_range')]
    public function searchProductByPriceRange(ProductRepository $productRepository, int $min, int $max): Response
    {
        // Find products by price range
        $products = $productRepository->findByPriceRange($min, $max);

        // Check if there are any products
        if (empty($products)) {
            return new Response('No products found', Response::HTTP_NOT_FOUND);
        }

        // Convert products to a string representation (or JSON)
        // Here, we'll use JsonResponse for better structure
        $productData = [];
        foreach ($products as $product) {
            $productData[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'price' => $product->getPrice(),
            ];
        }

        return new JsonResponse($productData); // Using JsonResponse for structured output
    }

    #[Route('/product/search-price-range-ordered/{min}/{max}', name: 'product_search_price_range_ordered')]
    public function searchProductByPriceRangeOrdered(ProductRepository $productRepository, int $min, int $max): Response
    {
        // Find products by price range and order by price
        $products = $productRepository->findByPriceRangeOrdered($min, $max);

        // Check if there are any products
        if (empty($products)) {
            return new Response('No products found', Response::HTTP_NOT_FOUND);
        }

        // Convert products to a string representation (or JSON)
        // Here, we'll use JsonResponse for better structure
        $productData = [];
        foreach ($products as $product) {
            $productData[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'price' => $product->getPrice(),
            ];
        }

        return new JsonResponse($productData); // Using JsonResponse for structured output
    }

    #[Route('/product/search-price-range-ordered-desc/{min}/{max}', name: 'product_search_price_range_ordered_desc')]
    public function searchProductByPriceRangeOrderedDesc(ProductRepository $productRepository, int $min, int $max): Response
    {
        // Find products by price range and order by price (descending)
        $products = $productRepository->findByPriceRangeOrderedDesc($min, $max);

        // Check if there are any products
        if (empty($products)) {
            return new Response('No products found', Response::HTTP_NOT_FOUND);
        }

        // Convert products to a string representation (or JSON)
        // Here, we'll use JsonResponse for better structure
        $productData = [];
        foreach ($products as $product) {
            $productData[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'price' => $product->getPrice(),
            ];
        }

        return new JsonResponse($productData); // Using JsonResponse for structured output
    }
    
}
?>