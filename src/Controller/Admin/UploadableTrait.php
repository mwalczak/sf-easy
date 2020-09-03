<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\UploadableInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

trait UploadableTrait
{
    /**
     * @Route("/upload")
     */
    public function upload(Request $request, Session $session): Response
    {
        return $this->uploadImage($request, $session);
    }

    /**
     * @Route("/{id}/upload")
     */
    public function entityUpload(int $id, Request $request, Session $session): Response
    {
        /** @var UploadableInterface $entity */
        $entity = $this->getDoctrine()->getManager()->getRepository(self::getEntityFqcn())->find($id);

        return $this->uploadImage($request, $session, $entity);
    }

    private function getEntityShortName(): string
    {
        return (new \ReflectionClass($this->getEntityFqcn()))->getShortName();
    }

    private function uploadImage(Request $request, Session $session, UploadableInterface $entity = null): Response
    {
        /** @var UploadedFile $blob */
        $blob = $request->files->get('data');
        if ($blob) {
            $fileName = $this->getEntityShortName().'_'.$this->getUser()->getId().'_'.uniqid().'.png';
            try {
                $blob->move(
                    dirname(__FILE__, 4).'/upload/',
                    $fileName
                );
                /** @var array $issues */
                $issues = $session->get($this->getEntityShortName(), []);
                $issues[] = $fileName;
                $session->set($this->getEntityShortName(), $issues);
            } catch (FileException $e) {
                return new Response('file upload error', 500);
            }
        } else {
            return new Response('no file uploaded', 500);
        }

        return new Response();
    }

    /**
     * @Route("/{id}/image/{file}")
     */
    public function image(int $id, string $file): Response
    {
        /** @var UploadableInterface $entity */
        $entity = $this->getDoctrine()->getManager()->getRepository(self::getEntityFqcn())->find($id);

        $this->denyAccessUnlessGranted('view', $entity);

        return new BinaryFileResponse(dirname(__FILE__, 4).'/upload/'.$file);
    }

    /**
     * @Route("/{id}/images")
     */
    public function images(int $id): Response
    {
        $entity = $this->getDoctrine()->getManager()->getRepository(self::getEntityFqcn())->find($id);

        $this->denyAccessUnlessGranted('view', $entity);

        return $this->json($entity->getImages());
    }

    /**
     * @param UploadableInterface $entityInstance
     */
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->handleUploadedImages($entityInstance);
        $entityManager->persist($entityInstance);
        $entityManager->flush();
    }

    /**
     * @param UploadableInterface $entityInstance
     */
    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->handleUploadedImages($entityInstance);
        $entityManager->persist($entityInstance);
        $entityManager->flush();
    }

    private function handleUploadedImages(UploadableInterface $entity): void
    {
        $images = $this->container->get('session')->get($this->getEntityShortName());
        if ($images) {
            $entity->addImages($images);
            $this->container->get('session')->remove($this->getEntityShortName());
        }
    }
}
