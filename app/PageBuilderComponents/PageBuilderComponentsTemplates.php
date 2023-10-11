<?php

namespace App\PageBuilderComponents;

class PageBuilderComponentsTemplates
{
    public function index(): array
    {
        return [
            [
                "user_id" => 1,
                "title" => "Misty shroud over a forest",
                "html_code" =>
                    "<section><div class=\"w-full md:pt-12 md:pb-12 pt-4 pb-4\"><div class=\"mx-auto max-w-7xl\">\n<img class=\"w-full\" src=\"https://images.unsplash.com/photo-1418065460487-3e41a6c84dc5?ixlib=rb-4.0.3&amp;ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&amp;auto=format&amp;fit=crop&amp;w=2200&amp;q=80\"><h3>Hello &amp; Bonjour</h3><div><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi at tellus nec lacus dignissim molestie nec non odio. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi at tellus nec lacus dignissim molestie nec non odio.</p></div> </div></div></section>",
                "published" => 1,
            ],
            [
                "user_id" => 1,
                "title" =>
                    "Showcase Content with 4 Stunning Images and Descriptions",
                "html_code" =>
                    "<section><div class=\"w-full md:pt-12 md:pb-12 pt-4 pb-4\"><div class=\"mx-auto max-w-7xl\"><div class=\"grid grid-flow-col gap-2\"> <div> <img class=\"object-cover w-full object-top aspect-square\" src=\"https://images.unsplash.com/photo-1553448539-a13a5595a494?crop=entropy&amp;cs=tinysrgb&amp;fit=max&amp;fm=jpg&amp;ixid=M3wzMTI0Mzl8MHwxfHNlYXJjaHw4Nnx8YXJjaGl0ZWN0dXJlfGVufDB8fHx8MTY5NTAzMDc5MXww&amp;ixlib=rb-4.0.3&amp;q=80&amp;w=1080\" alt=\"provider\"> <p> Ullamcorper dignissim cras tincidunt lobortis. Magna etiam tempor orci eu lobortis.</p> </div> <div> <img class=\"object-cover w-full object-top aspect-square\" src=\"https://images.unsplash.com/photo-1483366774565-c783b9f70e2c?crop=entropy&amp;cs=tinysrgb&amp;fit=max&amp;fm=jpg&amp;ixid=M3wzMTI0Mzl8MHwxfHNlYXJjaHw1Mnx8YnVpbGRpbmd8ZW58MHx8fHwxNjk1MDMwNzYyfDA&amp;ixlib=rb-4.0.3&amp;q=80&amp;w=1080\" alt=\"provider\"> <p> Ullamcorper dignissim cras tincidunt lobortis. Magna etiam tempor orci eu lobortis.</p> </div> <div> <img class=\"object-cover w-full object-top aspect-square\" src=\"https://images.unsplash.com/photo-1522743791393-522312deeebf?crop=entropy&amp;cs=tinysrgb&amp;fit=max&amp;fm=jpg&amp;ixid=M3wzMTI0Mzl8MHwxfHNlYXJjaHw2Mnx8YnVpbGRpbmd8ZW58MHx8fHwxNjk1MDMwNzYyfDA&amp;ixlib=rb-4.0.3&amp;q=80&amp;w=1080\" alt=\"provider\"> <p> Ullamcorper dignissim cras tincidunt lobortis. Magna etiam tempor orci eu lobortis.</p> </div> <div> <img class=\"object-cover w-full object-top aspect-square\" src=\"https://images.unsplash.com/photo-1520587393050-c5298e1a8486?crop=entropy&amp;cs=tinysrgb&amp;fit=max&amp;fm=jpg&amp;ixid=M3wzMTI0Mzl8MHwxfHNlYXJjaHw3NXx8YXJjaGl0ZWN0dXJlfGVufDB8fHx8MTY5NTAzMDc5MHww&amp;ixlib=rb-4.0.3&amp;q=80&amp;w=1080\" alt=\"provider\"> <p> Ullamcorper dignissim cras tincidunt lobortis. Magna etiam tempor orci eu lobortis.</p> </div> </div> </div> </div></section>",
                "published" => 1,
            ],
        ];
    }
}
