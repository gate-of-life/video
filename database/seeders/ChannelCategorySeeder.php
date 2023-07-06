<?php

namespace Database\Seeders;

use App\Models\ChannelCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChannelCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            'Worship and Liturgy',
            'Sermons and Homilies',
            'Theology and Doctrine',
            'Church History',
            'Iconography and Sacred Art',
            'Spiritual Practices and Guidance',
            'Saints and Martyrs',
            'Orthodox Church Architecture',
            'Orthodoxy and Contemporary Issues',
            'Hymns and Chanting',
            'Bible Study and Exegesis',
            'Spiritual Reflections',
            'Orthodox Christian Traditions and Customs',
            'Apologetics',
            'Orthodox Christian Books and Authors',
            'Monastic Life and Spirituality',
            'Ecumenism and Interfaith Dialogue',
            'Family and Parenting',
            'Missions and Outreach',
            'Youth Content',
            'Other',
        ];

        foreach ($items as $item) {
            $category = new ChannelCategory();
            $category->name = $item;
            $category->save();
        }
    }
}
