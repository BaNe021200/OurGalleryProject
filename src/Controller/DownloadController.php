<?php

namespace App\Controller;

use App\Model\ImageManager;
use App\Model\Manager;
use App\Model\ThumbnailManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Thumbnails;
use App\Entity\Images;
use App\Controller\ImagesController;

class DownloadController extends Controller
{





    public function update($item)
    {

        $messages = [];
        $imgs = glob('img/' . $item . '/*.jpg');
        foreach ($imgs as $img) {
            $imgBasename[] = basename($img);
            $srcSize= filesize($img);

            if($srcSize['error']==2){$messages[] = "Le fichier ".$imgBasename." est trop volumineux";}
            if($srcSize['error']==1){$messages[]= 'votre fichier excède la taille de configuration du serveur.Veuillez Uploader un fichier < à 1.4mo ';}
        }

        //var_dump($imgBasename);die;
        $thumbs = glob('img/' . $item . '/thumbs/*.jpg');
        foreach ($thumbs as $thumb) {
            $thumbBasename[] = basename($thumb);
        }



        @$data = array_diff(@$imgBasename, @$thumbBasename);

        if(empty($imgBasename)){
            $messages[]="Votre dossier photos est vide";
            $explo=  exec("C:\WINDOWS\\explorer.exe /e,/select,C:\wamp64\www\PhpTraining\pinterest\pinterest2\public\img\\".$item."\\thumbs");
            return $this->render('images/' . $item . '/success' . $item . '.html.twig', [
                'message' => $messages,

            ]);
        }elseif(empty($thumbBasename)){

            return $this->firstCropImagesForUpdate($item);



        }else
        {



            foreach ($data as $datum) {
                $pictures[] = 'img/' . $item . '/' . $datum; //var_dump($data);die;
            }


            if (!empty($data)) {



                $images = $pictures;
                foreach ($images as $image) {


                    $src = $image;
                    $srcName = basename($src);
                    $srcSize= filesize($src);
                    if($srcSize > 1600000 )
                    {$messages[] =nl2br("Le fichier ".$srcName." est trop volumineux\r\n ");


                    }else {

                        $infoName = pathinfo($src);
                        $cropName = $infoName['basename'];//var_dump($image);die;
                        $image = imagecreatefromjpeg($src);
                        $size = min(imagesx($image), imagesy($image));
                        $im2 = imagecrop($image, ['x' => 0, 'y' => 0, 'width' => $size, 'height' => $size]);
                        if ($im2 !== FALSE) {
                            imagejpeg($im2, 'img/' . $item . '/thumbs/' . $cropName);
                        }

                        $entityManager = $this->getDoctrine()->getManager();
                        $image = new Images();


                        $image->setDirname('img/' . $item);
                        $image->setBasename($cropName);
                        $entityManager->persist($image);
                        $entityManager->flush();
                        $imageId = $image->getId();

                        $thumbnail = new Thumbnails();
                        $thumbnail->setImagesId($imageId);
                        $thumbnail->setDirname('img/' . $item . '/thumbs/');
                        $thumbnail->setBasename($cropName);
                        $entityManager->persist($thumbnail);
                        $entityManager->flush();
                        if(@$thumbnail)
                        {
                            $messages[]= nl2br("le fichier ".$srcName." a été uploadé\r\n");
                        }

                    }



                }

            }else {
                $messages[] = "Il n'y a rien à mettre à jour";
            }
            return $this->render('images/' . $item . '/success' . $item . '.html.twig', [
                'message' => $messages,
            ]);
        }
    }

    public function firstCropImagesForUpdate($item){



        $images=glob('img/'.$item.'/*.jpg');


        $messages=[];
        foreach ($images as $image){


            $src= $image;
            $infoName= pathinfo($src);
            $cropName=$infoName['basename'];
            $image = imagecreatefromjpeg($src);
            $size = min(imagesx($image), imagesy($image));
            $im2 = imagecrop($image, ['x' => 0, 'y' => 0, 'width' => $size, 'height' => $size]);
            if ($im2 !== FALSE) {
                imagejpeg($im2, 'img/'.$item.'/thumbs/' . $cropName);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $image = new Images();


            $image->setDirname('img/'.$item);
            $image->setBasename($cropName);
            $entityManager->persist($image);
            $entityManager->flush();
            $imageId= $image->getId();

            $thumbnail = new Thumbnails();
            $thumbnail->setImagesId($imageId);
            $thumbnail->setDirname('img/'.$item.'/thumbs/');
            $thumbnail->setBasename($cropName);
            $entityManager->persist($thumbnail);
            $entityManager->flush();
        }
        if($thumbnail){
            $messages[]="Vos photos sont maintenant à jours";
        }else{
            $messages[]="Une erreur est survenue, imossible de mettre vos photos à jour";
        }
        return $this->render('images/' . $item . '/success' . $item . '.html.twig', [
            'message' => $messages,
        ]);

    }

    public function updateDoudounes()
    {
        return $this->update('doudounes');

    }

    public function updateDesigual()
    {
        return $this->update('desigual');

    }

    public function updateFurWomen()
    {
        return $this->update('fursWomen');

    }

    public function updateFurMen()
    {
        return $this->update('fursMen');

    }

    public function updatePulls()
    {
        return $this->update('Pulls');

    }

    public function updateCreapulka()
    {
        return $this->update('Creapulka');

    }

    public function updateDelicious_Sev()
    {
        return $this->update('Delicious_Sev');

    }

    public function updateWax()
    {
        return $this->update('Wax');

    }


    /*Fonction EraseModal*/

    public function eraseModal($item,$basename,$id,$images_id)
    {

        return $this->render('images/'.$item.'/erase'.$item.'.html.twig',[
            'basename' => $basename,
            'id' =>$id ,
            'images_Id'=>$images_id
        ]);
    }

    public function eraseModalDoudoune($basename,$id,$images_id)
    {
        return($this->eraseModal('Doudounes',$basename,$id,$images_id));
    }

    public function eraseModalDesigual($basename,$id,$images_id)
    {
        return($this->eraseModal('Desigual',$basename,$id,$images_id));
    }

    public function eraseModalWax($basename,$id,$images_id)
    {
        return($this->eraseModal('Wax',$basename,$id,$images_id));
    }

    public function eraseModalPulls($basename,$id,$images_id)
    {
        return($this->eraseModal('Pulls',$basename,$id,$images_id));
    }

    public function eraseModalCreapulka($basename,$id,$images_id)
    {
        return($this->eraseModal('Creapulka',$basename,$id,$images_id));
    }

    public function eraseModalDelicious_Sev($basename,$id,$images_id)
    {
        return($this->eraseModal('Delicious_Sev',$basename,$id,$images_id));
    }

    public function eraseModalFursWomen($basename,$id,$images_id)
    {
        return($this->eraseModal('FursWomen',$basename,$id,$images_id));
    }

    public function eraseModalFursMen($basename,$id,$images_id)
    {
        return($this->eraseModal('FursMen',$basename,$id,$images_id));
    }


    /*fonction destroy one*/


    public function destroy($item,$id,$images_id)
    {
        $messages=[];
        $tManager = new ThumbnailManager();
        $destroyThumb = $tManager->destroyThumb($id);
        if($destroyThumb)
        {
            $messages[]="La miniature a bien été détruite !</br> ";
        }
        else{
            $messages[]="Une erreur a surgit du fond de la nuit. La miniature n'a pu être détruite";
        }

        $iManager = new ImageManager();
        $destroyImg = $iManager->destroyImg($images_id);
        if($destroyImg){
            $messages[]="L'image a bien été détruite !";
        }
        else{
            $messages[]="Une erreur a surgit du fond de la nuit. L'image n'a pu être détruite";
        }


        return $this->render('images/'.$item.'/success'.$item.'.html.twig',[
            'message' => $messages

        ]);

        /*return $this->render('images/'.$item.'/eraseSuccess'.$item.'.html.twig');*/

    }

    public function destroyDoudoune($id,$images_id)
    {
        return($this->destroy('Doudounes',$id,$images_id));
    }

    public function destroyDesigual($id,$images_id)
    {
        return($this->destroy('Desigual',$id,$images_id));
    }

    public function destroyWax($id,$images_id)
    {
        return($this->destroy('Wax',$id,$images_id));
    }

    public function destroyPulls($id,$images_id)
    {
        return($this->destroy('Pulls',$id,$images_id));
    }

    public function destroyCreapulka($id,$images_id)
    {
        return($this->destroy('Creapulka',$id,$images_id));
    }

    public function destroyDelicious_Sev($id,$images_id)
    {
        return($this->destroy('Delicious_Sev',$id,$images_id));
    }

    public function destroyFursWomen($id,$images_id)
    {
        return($this->destroy('FursWomen',$id,$images_id));
    }

    public function destroyFursMen($id,$images_id)
    {
        return($this->destroy('FursMen',$id,$images_id));
    }


    /* fonction EraseAll*/

    public function eraseModalAll($item)
    {

        return $this->render('images/'.$item.'/eraseAll'.$item.'.html.twig',[
            'theme'=> $item

        ]);
    }

    public function eraseModalDoudounesAll()
    {
        return $this->eraseModalAll('Doudounes');
    }

    public function eraseModalDesigualAll()
    {
        return $this->eraseModalAll('Desigual');
    }

    public function eraseModalFursWomenAll()
    {
        return $this->eraseModalAll('fursWomen');
    }



    /*Fonction destroy all*/

    public function destroyAll($item)
    {
        $messages=[];
        $tManager = new ThumbnailManager();
        $destroyThumbs = $tManager->destroyThumbsAll($item);
        if($destroyThumbs)
        {
            $messages[]="Les miniatures ont bien été détruite !</br> ";
        }
        else{
            $messages[]="Une erreur a surgit du fond de la nuit. Les miniatures n'ont pu être détruite";
        }

        $iManager = new ImageManager();
        $destroyImgs = $iManager->destroyImgAll($item);
        if($destroyImgs){
            $messages[]="Les images ont bien été détruite !";
        }
        else{
            $messages[]="Une erreur a surgit du fond de la nuit. Les images n'ont pu être détruite";
        }


        return $this->render('images/'.$item.'/success'.$item.'.html.twig',[
            'message' => $messages

        ]);

        /*return $this->render('images/'.$item.'/eraseSuccess'.$item.'.html.twig');*/

    }

    public function destroyDoudounesAll()
    {
        return $this->destroyAll('Doudounes');
    }

    public function destroyDesigualAll()
    {
        return $this->destroyAll('Desigual');
    }

    public function destroyFursWomenAll()
    {
        return $this->destroyAll('fursWomen');
    }
}

