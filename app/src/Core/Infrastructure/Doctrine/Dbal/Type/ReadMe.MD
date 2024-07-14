 # Требования к классу типа DBAL
 
1. Наследоваться от Doctrine\DBAL\Types\Type или от его дочернего класса
2. Переопределить метод

`public function getName()`
3. Переопределить метод(-ы) при необходимости:

   3.1. `public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)`

   3.2. `public function convertToPHPValue($value, AbstractPlatform $platform)` 

   3.3. `public function convertToDatabaseValue($value, AbstractPlatform $platform)`

Пример из руководства: 

https://www.doctrine-project.org/projects/doctrine-orm/en/3.1/cookbook/custom-mapping-types.html
