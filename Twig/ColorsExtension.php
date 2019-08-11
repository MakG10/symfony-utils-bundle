<?php

namespace MakG\SymfonyUtilsBundle\Twig;


use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class ColorsExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('light_colors', [$this, 'getLightColors']),
        ];
    }

    public function getFilters()
    {
        return [
            new TwigFilter('color', [$this, 'getRandomConstantColor']),
        ];
    }

    /**
     * Returns a color for a filtered string. The color is the same for equal strings.
     */
    public function getRandomConstantColor(?string $input, array $colors = []): string
    {
        if (empty($colors)) {
            return '#'.substr(md5($input), 0, 6);
        }


        $hash = crc32($input);

        return $colors[$hash % count($colors)];
    }

    /**
     * Returns array of light colors.
     */
    public function getLightColors(int $limit = 100): array
    {
        $colors = [];

        for ($i = 0; $i < $limit; $i++) {
            $colors[] = $this->getRandomLightColor();
        }

        return $colors;
    }

    /**
     * Returns random light color in hex form. #FFFFFF
     */
    private function getRandomLightColor(): string
    {
        return $this->getRandomColor([127, 255]);
    }

    private function getRandomColor(array $range): string
    {
        $color = '#';

        for ($i = 1; $i <= 3; $i++) {
            $color .= str_pad(dechex(random_int(...$range)), 2, '0', STR_PAD_LEFT);
        }

        return $color;
    }
}
