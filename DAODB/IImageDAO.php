<?php
    namespace DAODB;

    use Models\Image as Image;

    interface IImageDAO
    {
        function Add(Image $image);
        function GetAll();
        function GetByImageId($imageId);
    }
?>