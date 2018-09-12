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

    //fonction download
    public function download($item)
    {
        return $this->render('images/'.$item.'/download'.$item.'.html.twig',[

        ]);
    }

    public function downloadDoudounes()
    {

        return ($this->download("Doudounes"));
    }

    public function downloadFursWomen()
    {

        return ($this->download("fursWomen"));
    }

    public function downloadFursMen()
    {

        return ($this->download("fursMen"));
    }

    public function downloadPulls()
    {

        return ($this->download('Pulls'));
    }

    public function downloadCreapulka()
    {

        return ($this->download('Creapulka'));
    }

    public function downloadDelicious_Sev()
    {

        return ($this->download('Delicious_Sev'));
    }

    public function downloadWax()
    {

        return ($this->download("Wax"));
    }

    //fonction update
    public function getImages($item){
        return $this->cropUpdate($item);
    }

    public function cropUpdate($item)
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

            return $this->cropImagesUploadForUpdate($item);



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

    public function updateDoudounes()
    {
        return $this->getImages('doudounes');

    }

    public function updateFurWomen()
    {
        return $this->getImages('fursWomen');

    }

    public function updateFurMen()
    {
        return $this->getImages('fursMen');

    }

    public function updatePulls()
    {
        return $this->getImages('Pulls');

    }

    public function updateCreapulka()
    {
        return $this->getImages('Creapulka');

    }

    public function updateDelicious_Sev()
    {
        return $this->getImages('Delicious_Sev');

    }

    public function updateWax()
    {
        return $this->getImages('Wax');

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

    /*fonction destroy*/


    public function destroy($item,$id,$images_id)
    {
        $messages=[];
        $tManager = new ThumbnailManager();
        $destroyThumb = $tManager->destroyThumb($id);
        if($destroyThumb)
        {
            $messages[]="La miniature à bien été détruite !</br> ";
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




}

