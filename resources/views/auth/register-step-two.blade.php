<x-guest-layout>
    <x-auth-card>
        <x-slot name="cardHeader">
            Masukkan Data Diri Anda.
        </x-slot>

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('register-step-two') }}">
            @csrf
            <x-input type="hidden" name="email" :value="$req['email']" />

            <x-input type="hidden" name="password" :value="$req['password']" />

            <x-input type="hidden" name="profile_picture" :value="('default')" />

            <div class="mt-4">
                <x-label for="fullname" :value="__('Nama Lengkap')" />

                <x-input id="fullname" class="block mt-1 w-full" type="text" name="fullname" :value="old('fullname')"
                    required />
            </div>

            <div class="mt-4">
                <x-label for="phone" :value="__('Nomor Telepon')" />

                <x-input id="phone" class="block mt-1 w-full" type="tel" maxlength=12 name="phone" pattern="[0-9]{12}"
                    :value="old('phone')" required />
            </div>

            <div class="mt-4">
                <x-label :value="__('Jenis Kelamin')" />

                <div class="block">
                    <x-input id="laki" type="radio" name="gender" value="Laki-laki" required />

                    <x-label class="inline" for="laki" :value="__('Laki-laki')" />
                </div>

                <div class="block">
                    <x-input id="perempuan" type="radio" name="gender" value="Perempuan" required />

                    <x-label class="inline" for="perempuan" :value="__('Perempuan')" />
                </div>
            </div>

            <div x-data="getAlamat()" x-init="setBaseUrl('{{ url('/') }}'), fetchProvinsi()" class="mt-4">
                <x-label :value="__('Alamat')" />

                <x-select required x-model="selectedProv" x-on:change="changeProv()" x-bind:disabled="provDisable"
                    name="province_id">
                    <option value="">--Provinsi--</option>

                    <template x-for="prov in dataProvinsi">
                        <option :value="prov.province_id" x-text="prov.province_name"></option>
                    </template>
                </x-select>

                <x-select required x-model="selectedKab" x-on:change="changeKab()" x-bind:disabled="kabDisable"
                    name="city_id" id="kabupaten">
                    <option value="">--Kabupaten--</option>

                    <template x-for="kab in dataKabupaten">
                        <option :value="kab.city_id" x-text="kab.city_name"></option>
                    </template>
                </x-select>

                <x-select required x-bind:disabled="kecDisable" name="subdistrict_id" id="kecamatan">
                    <option value="">--Kecamatan--</option>

                    <template x-for="kec in dataKecamatan">
                        <option :value="kec.subdistrict_id" x-text="kec.subdistrict_name"></option>
                    </template>
                </x-select>
            </div>

            <div class="mt-4">
                <x-label for="address_detail" :value="__('Detail Alamat')" />

                <textarea class="block mt-1 w-full h-20 rounded-md" name="address_detail" id="address_detail"
                    required></textarea>
            </div>


            <div class="flex items-center justify-end mt-4">
                <x-button class="ml-4">
                    Daftar
                </x-button>
            </div>

        </form>
        <x-slot name="cardFooter">
            Sudah punya akun?
            <a class="underline  text-gray-300 hover:text-gray-100" href="{{ route('login') }}">Masuk</a>
        </x-slot>
    </x-auth-card>
</x-guest-layout>

<script>
    function getAlamat() {
        return {
            dataProvinsi: null,
            dataKabupaten: null,
            dataKecamatan: null,
            provDisable: true,
            kabDisable: true,
            kecDisable: true,
            selectedProv: null,
            selectedKab: null,
            baseUrl: null,
            setBaseUrl(url) {
                this.baseUrl = url;
            },
            changeProv() {
                this.dataKabupaten = null;
                this.dataKecamatan = null;
                this.fetchKabupaten();
                this.kabDisable = true;
                this.kecDisable = true;
            },
            changeKab() {
                this.dataKecamatan = null;
                this.fetchKecamatan();
                this.kecDisable = true;
            },
            fetchProvinsi() {
                fetch(`${this.baseUrl}/api/address/provinces`)
                    .then(res => res.json())
                    .then(data => {
                        this.dataProvinsi = data;
                        this.provDisable = false;

                        // console.log(this.dataProvinsi);
                    });
            },
            fetchKabupaten() {
                fetch(`${this.baseUrl}/api/address/provinces/${this.selectedProv}/cities`)
                    .then(res => res.json())
                    .then(data => {
                        this.dataKabupaten = data;
                        this.kabDisable = false;

                        // console.log(this.dataKabupaten);
                    })
            },
            fetchKecamatan() {
                fetch(`${this.baseUrl}/api/address/cities/${this.selectedKab}/subdistricts`)
                    .then(res => res.json())
                    .then(data => {
                        this.dataKecamatan = data;
                        this.kecDisable = false;

                        // console.log(this.dataKecamatan);
                    })
            }
        }
    }

</script>
