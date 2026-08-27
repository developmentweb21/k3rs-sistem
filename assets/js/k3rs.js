(function () {
	"use strict";
	var data = window.K3RS_DATA || {};
	var employeeRows = [];
	var menuRows = [];
	var roleRows = [];

	// Menyimpan status filter Verifikasi yang sedang aktif
	var currentVerificationStatus = "";
	function options(items) {
		return (
			'<option value="">-- Pilih --</option>' +
			items
				.map(function (item) {
					return "<option>" + item + "</option>";
				})
				.join("")
		);
	}
	function toast(message) {
		var el = document.createElement("div");
		el.className = "toast";
		el.textContent = message;
		document.getElementById("toast-container").appendChild(el);
		setTimeout(function () {
			el.remove();
		}, 3000);
	}
	function view(name) {
		document.querySelectorAll("[data-view]").forEach(function (el) {
			el.classList.toggle("hidden", el.dataset.view !== name);
		});

		document.querySelectorAll("[data-menu]").forEach(function (el) {
			el.classList.toggle("bg-blue-800", el.dataset.menu === name);
			el.classList.toggle("active", el.dataset.menu === name);
		});

		if (name === "verifikasi") {
			loadVerification(currentVerificationStatus);
		}

		if (name === "riwayat-insiden") {
			loadIncidentHistory();
		}
		if (name === "riwayat-checklist") {
			loadChecklistHistory();
		}
	}
	function render() {
		document.getElementById("sidebar-user-name").textContent = data.user.nama;
		document.getElementById("sidebar-user-role").textContent =
			data.user.role === "admin" ? "Administrator" : "Petugas Unit";
		loadNavigation();
		document.getElementById("lap-kategori").innerHTML = options(data.kategori);
		document.getElementById("check-unit").innerHTML = options(data.unit);
		document.getElementById("pegawai-unit").innerHTML = options(data.unit);
		document.getElementById("lap-sehat-unit").value = data.user.unit;
		document.getElementById("lap-sehat-nama").innerHTML = options([
			data.user.nama,
			"Siti Aminah",
			"Budi Santoso",
		]);
		document.getElementById("checklist-items").innerHTML = data.checklist
			.map(function (item, index) {
				var checklistId = item.id || index + 1;
				var checklistNama = item.nama || item;

				return (
					'<div class="border border-gray-200 bg-white rounded-lg p-4 mb-3">' +
					'<div class="font-medium text-gray-800 mb-3">' +
					(index + 1) +
					". " +
					escapeHtml(checklistNama) +
					"</div>" +
					'<div class="flex items-center gap-6">' +
					'<label class="flex items-center gap-2 cursor-pointer">' +
					'<input type="radio" ' +
					'name="checklist-' +
					checklistId +
					'" ' +
					'value="yes" required>' +
					'<span class="text-green-700 font-medium">' +
					'<i class="fa-solid fa-circle-check mr-1"></i> Ya' +
					"</span>" +
					"</label>" +
					'<label class="flex items-center gap-2 cursor-pointer">' +
					'<input type="radio" ' +
					'name="checklist-' +
					checklistId +
					'" ' +
					'value="no">' +
					'<span class="text-red-600 font-medium">' +
					'<i class="fa-solid fa-circle-xmark mr-1"></i> Tidak' +
					"</span>" +
					"</label>" +
					"</div>" +
					"</div>"
				);
			})
			.join("");
		if (data.user.role === "admin") {
			loadMasterData();
			loadEmployees();
			loadRoles();
		}
		document.getElementById("kpi-total").textContent = data.kpi.total;
		document.getElementById("kpi-near-miss").textContent = data.kpi.near_miss;
		document.getElementById("kpi-b3").textContent = data.kpi.b3;
		document.getElementById("kpi-kepatuhan").textContent =
			data.kpi.kepatuhan + "%";
		document.querySelectorAll("[data-menu]").forEach(function (el) {
			el.addEventListener("click", function () {
				view(el.dataset.menu);
			});
		});
		new Chart(document.getElementById("chart-insiden"), {
			type: "bar",
			data: {
				labels: ["Mar", "Apr", "Mei", "Jun", "Jul", "Agu"],
				datasets: [
					{
						label: "Insiden",
						data: [2, 4, 1, 5, 2, 3],
						backgroundColor: "#3b82f6",
						borderRadius: 4,
					},
				],
			},
		});
		new Chart(document.getElementById("chart-kategori"), {
			type: "doughnut",
			data: {
				labels: ["Near Miss", "Accident Man", "Laka Lantas", "B3"],
				datasets: [
					{
						data: [4, 3, 2, 1],
						backgroundColor: ["#3b82f6", "#f59e0b", "#ef4444", "#10b981"],
					},
				],
			},
		});
	}
	function api(path, payload, method) {
		method = method || "POST";
		var request = {
			method: method,
			headers: { "Content-Type": "application/json" },
		};
		if (method !== "GET") request.body = JSON.stringify(payload || {});
		return fetch(window.K3RS_API_URL + path, request).then(function (response) {
			return response.json().then(function (body) {
				if (!response.ok)
					throw new Error(body.message || "Terjadi kesalahan server.");
				return body;
			});
		});
	}
	function bindNavigation() {
		document.querySelectorAll("[data-menu]").forEach(function (el) {
			el.addEventListener("click", function () {
				view(el.dataset.menu);
			});
		});
	}
	function loadNavigation() {
		api("master/menu/navigasi", null, "GET")
			.then(function (response) {
				var menus = response.menus || [];

				// Menu utama
				var parents = menus.filter(function (menu) {
					return !menu.parent_id;
				});

				var html = "";

				parents.forEach(function (parent) {
					// Cari submenu berdasarkan parent_id
					var children = menus.filter(function (menu) {
						return String(menu.parent_id) === String(parent.id);
					});

					/*
					 * MENU DENGAN SUBMENU
					 */
					if (children.length > 0) {
						html +=
							'<div class="menu-group">' +
							'<button type="button" ' +
							'class="menu-button w-full text-left px-3 py-2 rounded hover:bg-blue-800 flex items-center justify-between" ' +
							'data-toggle-menu="' +
							parent.id +
							'">' +
							"<span>" +
							'<i class="fa-solid ' +
							escapeHtml(parent.icon) +
							' w-6"></i>' +
							escapeHtml(parent.nama) +
							"</span>" +
							'<i class="fa-solid fa-chevron-down text-xs transition-transform"></i>' +
							"</button>" +
							'<div class="submenu hidden pl-5" data-submenu="' +
							parent.id +
							'">';

						children.forEach(function (child) {
							html +=
								"<button " +
								'data-menu="' +
								escapeHtml(child.slug) +
								'" ' +
								'class="menu-button w-full text-left px-3 py-2 rounded hover:bg-blue-800">' +
								'<i class="fa-solid ' +
								escapeHtml(child.icon) +
								' w-6"></i>' +
								escapeHtml(child.nama) +
								"</button>";
						});

						html += "</div>" + "</div>";

						/*
						 * MENU BIASA
						 */
					} else {
						html +=
							'<button data-menu="' +
							escapeHtml(parent.slug) +
							'" ' +
							'class="menu-button w-full text-left px-3 py-2 rounded hover:bg-blue-800">' +
							'<i class="fa-solid ' +
							escapeHtml(parent.icon) +
							' w-6"></i>' +
							escapeHtml(parent.nama) +
							"</button>";
					}
				});

				document.getElementById("nav-menu").innerHTML = html;

				/*
				 * Event dropdown
				 */
				document
					.querySelectorAll("[data-toggle-menu]")
					.forEach(function (button) {
						button.addEventListener("click", function () {
							var parentId = button.dataset.toggleMenu;

							var submenu = document.querySelector(
								'[data-submenu="' + parentId + '"]',
							);

							if (submenu) {
								submenu.classList.toggle("hidden");

								var icon = button.querySelector(".fa-chevron-down");

								if (icon) {
									icon.classList.toggle("rotate-180");
								}
							}
						});
					});

				/*
				 * Event klik menu
				 */
				bindNavigation();
			})
			.catch(function (error) {
				toast(error.message);
			});
	}
	function loadVerification(status) {
		if (typeof status !== "undefined") {
			currentVerificationStatus = status;
		}

		var endpoint = "laporan/verifikasi";

		if (currentVerificationStatus) {
			endpoint += "?status=" + encodeURIComponent(currentVerificationStatus);
		}

		api(endpoint, null, "GET")
			.then(function (response) {
				var rows = response.laporan || [];

				var table = document.getElementById("table-verifikasi");

				if (!table) {
					return;
				}

				table.innerHTML =
					rows
						.map(function (report) {
							var statusClass =
								report.status === "menunggu"
									? "bg-yellow-100 text-yellow-700"
									: report.status === "diproses"
										? "bg-blue-100 text-blue-700"
										: "bg-green-100 text-green-700";

							return (
								'<tr class="border-b border-gray-100 hover:bg-gray-50">' +
								'<td class="p-4">' +
								escapeHtml(report.tanggal_kejadian || "-") +
								"</td>" +
								'<td class="p-4">' +
								escapeHtml(report.pelapor || "-") +
								"</td>" +
								'<td class="p-4">' +
								escapeHtml(report.kategori || "-") +
								"</td>" +
								'<td class="p-4">' +
								escapeHtml(report.lokasi || "-") +
								"</td>" +
								'<td class="p-4">' +
								'<span class="px-3 py-1 rounded-full text-xs font-medium ' +
								statusClass +
								'">' +
								escapeHtml(report.status || "-") +
								"</span>" +
								"</td>" +
								'<td class="p-4 text-center">' +
								'<button class="text-blue-600 hover:text-blue-800 font-medium" ' +
								'data-verify="' +
								report.id +
								'">' +
								'<i class="fa-solid fa-eye mr-1"></i> Detail' +
								"</button>" +
								"</td>" +
								"</tr>"
							);
						})
						.join("") ||
					"<tr>" +
						'<td colspan="6" class="p-6 text-center text-gray-500">' +
						"Tidak ada laporan untuk status ini." +
						"</td>" +
						"</tr>";

				// Event tombol Detail
				document.querySelectorAll("[data-verify]").forEach(function (button) {
					button.addEventListener("click", function () {
						loadVerificationDetail(this.dataset.verify);
					});
				});

				updateVerificationFilter();
			})
			.catch(function (error) {
				console.error(error);

				toast(error.message || "Gagal memuat data laporan.");
			});
	}
	function loadVerificationDetail(id) {
		api("laporan/detail_verifikasi/" + id, null, "GET")
			.then(function (response) {
				var report = response.laporan;
				var tindakLanjut = response.tindak_lanjut || [];

				document.getElementById("detail-verifikasi-id").textContent =
					report.id || "-";

				document.getElementById("detail-verifikasi-tanggal").textContent =
					report.tanggal_kejadian || "-";

				document.getElementById("detail-verifikasi-kategori").textContent =
					report.kategori || "-";

				document.getElementById("detail-verifikasi-lokasi").textContent =
					report.lokasi || "-";

				document.getElementById("detail-verifikasi-created").textContent =
					report.created_at || "-";

				document.getElementById("detail-verifikasi-pelapor").textContent =
					report.pelapor || "-";

				document.getElementById("detail-verifikasi-kronologi").textContent =
					report.kronologi || "-";

				document.getElementById("detail-verifikasi-tindakan").textContent =
					report.tindakan_awal || "-";

				document.getElementById("detail-verifikasi-status").textContent =
					report.status || "-";

				renderTindakLanjut(tindakLanjut);
				// Atur tombol berdasarkan status laporan
				updateVerificationAction(report);

				// Tampilkan halaman detail
				view("detail-verifikasi");
			})
			.catch(function (error) {
				console.error("Gagal memuat detail laporan:", error);
				toast(error.message || "Gagal memuat detail laporan.");
			});
	}
	function renderTindakLanjut(items) {
		var card = document.getElementById("detail-tindak-lanjut-card");

		var container = document.getElementById("detail-tindak-lanjut-list");

		if (!card || !container) {
			return;
		}

		items = items || [];

		if (items.length === 0) {
			card.classList.add("hidden");
			container.innerHTML = "";
			return;
		}

		card.classList.remove("hidden");

		container.innerHTML = items
			.map(function (item) {
				var fotoHtml = "";

				if (item.foto_url) {
					fotoHtml =
						'<div class="mt-4">' +
						'<p class="text-sm text-gray-500 mb-2">' +
						"Foto Dokumentasi" +
						"</p>" +
						'<a href="' +
						escapeHtml(item.foto_url) +
						'" target="_blank" rel="noopener">' +
						'<img src="' +
						escapeHtml(item.foto_url) +
						'" alt="Foto dokumentasi" ' +
						'class="w-full max-w-md rounded-lg border ' +
						'hover:opacity-90 transition">' +
						"</a>" +
						"</div>";
				}

				return (
					'<div class="border border-gray-200 rounded-xl p-5">' +
					'<div class="flex justify-between items-start gap-3 mb-4">' +
					"<div>" +
					'<p class="font-semibold text-gray-800">' +
					'<i class="fa-solid fa-user-check text-blue-600 mr-2"></i>' +
					escapeHtml(item.verifikator || "Verifikator K3") +
					"</p>" +
					'<p class="text-sm text-gray-500 mt-1">' +
					'<i class="fa-regular fa-clock mr-1"></i>' +
					escapeHtml(item.created_at || "-") +
					"</p>" +
					"</div>" +
					'<span class="text-xs bg-blue-100 text-blue-700 px-3 py-1 rounded-full">' +
					"Tindak Lanjut" +
					"</span>" +
					"</div>" +
					'<div class="border-t pt-4">' +
					'<p class="text-sm text-gray-500 mb-2">' +
					"Keterangan / Tindakan Lanjutan" +
					"</p>" +
					'<div class="text-gray-700 whitespace-pre-line leading-relaxed">' +
					escapeHtml(item.keterangan || "-") +
					"</div>" +
					"</div>" +
					fotoHtml +
					"</div>" +
					'<div class="mt-4 pt-4 border-t flex justify-end">' +
					'<button type="button" data-edit-tindak-lanjut="' +
					item.id +
					'" class="text-sm px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white">' +
					'<i class="fa-solid fa-pen mr-2"></i>' +
					"Edit Tindak Lanjut" +
					"</button>" +
					"</div>"
				);
			})
			.join("");
	}

	function editTindakLanjut(id) {
		api("laporan/tindak-lanjut/" + id, {}, "GET")
			.then(function (response) {
				var item = response.tindak_lanjut;

				document.getElementById("edit-tindak-lanjut-id").value = item.id;

				document.getElementById("edit-tindak-lanjut-keterangan").value =
					item.keterangan || "";

				var modal = document.getElementById("modal-edit-tindak-lanjut");

				modal.classList.remove("hidden");
				modal.classList.add("flex");
			})
			.catch(function (error) {
				toast(error.message || "Gagal mengambil data tindak lanjut.");
			});
	}
	function closeEditTindakLanjut() {
		var modal = document.getElementById("modal-edit-tindak-lanjut");

		modal.classList.add("hidden");
		modal.classList.remove("flex");
	}
	function updateTindakLanjut() {
		var id = document.getElementById("edit-tindak-lanjut-id").value;

		var keterangan = document
			.getElementById("edit-tindak-lanjut-keterangan")
			.value.trim();

		var foto = document.getElementById("edit-tindak-lanjut-foto");

		if (!keterangan) {
			toast("Keterangan wajib diisi.");
			return;
		}

		var formData = new FormData();

		formData.append("keterangan", keterangan);

		if (foto.files.length > 0) {
			formData.append("foto", foto.files[0]);
		}

		var button = document.getElementById("btn-update-tindak-lanjut");

		button.disabled = true;

		fetch(window.K3RS_API_URL + "laporan/tindak-lanjut/update/" + id, {
			method: "POST",
			body: formData,
		})
			.then(function (response) {
				return response.json().then(function (data) {
					if (!response.ok) {
						throw new Error(data.message || "Gagal menyimpan perubahan.");
					}

					return data;
				});
			})
			.then(function (response) {
				toast(response.message);

				closeEditTindakLanjut();

				// Muat ulang detail laporan aktif
				var mainButton = document.getElementById("btn-verifikasi-laporan");

				loadVerificationDetail(mainButton.dataset.id);
			})
			.catch(function (error) {
				toast(error.message);
			})
			.finally(function () {
				button.disabled = false;
			});
	}
	var btnUpdateTindakLanjut = document.getElementById(
		"btn-update-tindak-lanjut",
	);

	if (btnUpdateTindakLanjut) {
		btnUpdateTindakLanjut.addEventListener("click", updateTindakLanjut);
	}

	document.addEventListener("click", function (event) {
		var editButton = event.target.closest("[data-edit-tindak-lanjut]");

		if (!editButton) {
			return;
		}

		editTindakLanjut(editButton.dataset.editTindakLanjut);
	});

	document.addEventListener("click", function (event) {
		var closeButton = event.target.closest("[data-close-edit-tindak-lanjut]");

		if (!closeButton) {
			return;
		}

		closeEditTindakLanjut();
	});

	//v1
	/* function updateVerificationAction(report) {
		var button = document.getElementById("btn-verifikasi-laporan");

		var statusInfo = document.getElementById("detail-verifikasi-status-info");

		var actionInfo = document.getElementById("detail-verifikasi-action-info");

		if (!button) {
			return;
		}

		// Simpan data aktif pada tombol
		button.dataset.id = report.id;
		button.dataset.status = report.status;

		button.disabled = false;

		// Reset class
		button.classList.remove(
			"hidden",
			"bg-green-600",
			"hover:bg-green-700",
			"bg-blue-600",
			"hover:bg-blue-700",
		);

		if (report.status === "menunggu") {
			statusInfo.textContent = "Laporan ini masih menunggu proses verifikasi.";

			actionInfo.textContent =
				"Verifikasi laporan untuk memulai proses tindak lanjut.";

			button.innerHTML =
				'<i class="fa-solid fa-check mr-2"></i> ' + "Verifikasi Laporan";

			button.classList.add("bg-green-600", "hover:bg-green-700");
		} else if (report.status === "diproses") {
			statusInfo.textContent = "Laporan sedang dalam proses tindak lanjut.";

			actionInfo.textContent =
				"Klik tombol di bawah jika seluruh tindak lanjut sudah selesai.";

			button.innerHTML =
				'<i class="fa-solid fa-check-double mr-2"></i> ' + "Selesaikan Laporan";

			button.classList.add("bg-blue-600", "hover:bg-blue-700");
		} else if (report.status === "selesai") {
			statusInfo.textContent =
				"Laporan telah selesai dan tidak memerlukan tindakan lanjutan.";

			actionInfo.textContent = "Tidak ada tindakan yang perlu dilakukan.";

			button.classList.add("hidden");
		}
	} */
	/* EVENT TOMBOL KEMBALI */
	/* EVENT HALAMAN DETAIL VERIFIKASI */

	var btnKembaliVerifikasi = document.getElementById("btn-kembali-verifikasi");

	if (btnKembaliVerifikasi) {
		btnKembaliVerifikasi.addEventListener("click", function () {
			view("verifikasi");
		});
	}

	var btnVerifikasi = document.getElementById("btn-verifikasi-laporan");

	if (btnVerifikasi) {
		btnVerifikasi.addEventListener("click", processVerification);
	}

	function processVerification() {
		var button = document.getElementById("btn-verifikasi-laporan");

		if (!button) {
			return;
		}

		var status = button.dataset.status;

		// Jika masih menunggu, tampilkan form tindak lanjut
		if (status === "menunggu") {
			var form = document.getElementById("form-tindak-lanjut");

			if (form) {
				form.classList.remove("hidden");

				// Scroll ke form agar langsung terlihat
				form.scrollIntoView({
					behavior: "smooth",
					block: "center",
				});
			}

			return;
		}

		// Jika sedang diproses, tetap jalankan proses selesai
		if (status === "diproses") {
			completeVerification();
		}
	}

	function updateVerificationFilter() {
		document
			.querySelectorAll(".btn-filter-verifikasi")
			.forEach(function (button) {
				var status = button.dataset.status || "";

				// Reset tampilan
				button.classList.remove("ring-2", "ring-blue-500", "font-bold");

				// Status aktif
				if (status === currentVerificationStatus) {
					button.classList.add("ring-2", "ring-blue-500", "font-bold");
				}
			});
	}
	document
		.querySelectorAll(".btn-filter-verifikasi")
		.forEach(function (button) {
			button.addEventListener("click", function () {
				loadVerification(this.dataset.status || "");
			});
		});

	//v2
	function updateVerificationAction(report) {
		var button = document.getElementById("btn-verifikasi-laporan");

		if (!button) {
			return;
		}

		// Simpan data laporan pada tombol
		button.dataset.id = report.id;
		button.dataset.status = report.status;

		button.disabled = false;

		// MENUNGGU
		if (report.status === "menunggu") {
			button.classList.remove("hidden");
			button.innerHTML =
				'<i class="fa-solid fa-check mr-2"></i>' + "Verifikasi Laporan";

			return;
		}

		// DIPROSES
		if (report.status === "diproses") {
			button.classList.remove("hidden");

			button.innerHTML =
				'<i class="fa-solid fa-check-double mr-2"></i>' + "Selesaikan Laporan";

			return;
		}

		// SELESAI
		if (report.status === "selesai") {
			button.classList.add("hidden");
		}
	}

	function completeVerification() {
		var button = document.getElementById("btn-verifikasi-laporan");

		if (!button) {
			return;
		}

		var id = button.dataset.id;

		if (!id) {
			toast("ID laporan tidak ditemukan.");
			return;
		}

		if (
			!confirm(
				"Apakah Anda yakin seluruh tindak lanjut telah selesai?\n\n" +
					"Status laporan akan berubah menjadi SELESAI.",
			)
		) {
			return;
		}

		var originalHtml = button.innerHTML;

		button.disabled = true;

		button.innerHTML =
			'<i class="fa-solid fa-spinner fa-spin mr-2"></i> ' + "Memproses...";

		api("laporan/verifikasi/proses/" + id, {}, "POST")
			.then(function (response) {
				toast(response.message || "Laporan berhasil diselesaikan.");

				view("verifikasi");
			})
			.catch(function (error) {
				console.error(error);

				toast(error.message || "Gagal menyelesaikan laporan.");

				button.disabled = false;
				button.innerHTML = originalHtml;
			});
	}

	var btnBatalTindakLanjut = document.getElementById("btn-batal-tindak-lanjut");

	if (btnBatalTindakLanjut) {
		btnBatalTindakLanjut.addEventListener("click", function () {
			var form = document.getElementById("form-tindak-lanjut");

			if (form) {
				form.classList.add("hidden");
			}
		});
	}

	function saveTindakLanjut() {
		var button = document.getElementById("btn-simpan-tindak-lanjut");

		var mainButton = document.getElementById("btn-verifikasi-laporan");

		var keterangan = document.getElementById("tindak-lanjut-keterangan");

		var foto = document.getElementById("tindak-lanjut-foto");

		if (!button || !mainButton || !keterangan) {
			return;
		}

		var id = mainButton.dataset.id;

		if (!id) {
			toast("ID laporan tidak ditemukan.");
			return;
		}

		if (!keterangan.value.trim()) {
			toast("Keterangan atau tindakan lanjutan wajib diisi.");

			keterangan.focus();
			return;
		}

		var formData = new FormData();

		formData.append("keterangan", keterangan.value.trim());

		if (foto && foto.files && foto.files.length > 0) {
			formData.append("foto", foto.files[0]);
		}

		var originalHtml = button.innerHTML;

		button.disabled = true;

		button.innerHTML =
			'<i class="fa-solid fa-spinner fa-spin mr-2"></i> ' + "Menyimpan...";

		/*
		 * Catatan:
		 * Fungsi api() lama kemungkinan mengirim JSON.
		 * Untuk upload file kita gunakan fetch langsung.
		 */

		fetch("api/laporan/verifikasi/proses/" + id, {
			method: "POST",
			body: formData,
		})
			.then(function (response) {
				return response.json().then(function (data) {
					if (!response.ok) {
						throw new Error(data.message || "Gagal menyimpan verifikasi.");
					}

					return data;
				});
			})
			.then(function (response) {
				toast(response.message || "Verifikasi berhasil disimpan.");

				// Reset form
				keterangan.value = "";

				if (foto) {
					foto.value = "";
				}

				// Sembunyikan form
				var form = document.getElementById("form-tindak-lanjut");

				if (form) {
					form.classList.add("hidden");
				}

				// Ambil ulang detail terbaru
				loadVerificationDetail(id);
			})
			.catch(function (error) {
				console.error(error);

				toast(error.message || "Gagal menyimpan verifikasi.");
			})
			.finally(function () {
				button.disabled = false;
				button.innerHTML = originalHtml;
			});
	}

	var btnSimpanTindakLanjut = document.getElementById(
		"btn-simpan-tindak-lanjut",
	);

	if (btnSimpanTindakLanjut) {
		btnSimpanTindakLanjut.addEventListener("click", saveTindakLanjut);
	}

	function escapeHtml(value) {
		return String(value || "").replace(/[&<>"']/g, function (char) {
			return {
				"&": "&amp;",
				"<": "&lt;",
				">": "&gt;",
				'"': "&quot;",
				"'": "&#039;",
			}[char];
		});
	}
	function loadEmployees() {
		api("master/users", null, "GET")
			.then(function (response) {
				employeeRows = response.users;
				document.getElementById("table-pegawai").innerHTML =
					employeeRows
						.map(function (user) {
							return (
								'<tr class="border-b"><td class="p-4">' +
								escapeHtml(user.username) +
								"</td><td>" +
								escapeHtml(user.nama_lengkap) +
								"</td><td>" +
								escapeHtml(user.unit_kerja) +
								'</td><td><span class="badge">' +
								(user.role === "admin" ? "Administrator" : "Petugas Unit") +
								'</span></td><td class="p-4 text-center whitespace-nowrap"><button class="text-blue-600 mr-3" data-edit-pegawai="' +
								user.id +
								'"><i class="fa-solid fa-pen"></i> Edit</button><button class="text-red-600" data-delete-pegawai="' +
								user.id +
								'"><i class="fa-solid fa-trash"></i> Hapus</button></td></tr>'
							);
						})
						.join("") ||
					'<tr><td class="p-4" colspan="5">Belum ada data pegawai.</td></tr>';
				document
					.querySelectorAll("[data-edit-pegawai]")
					.forEach(function (button) {
						button.addEventListener("click", function () {
							editEmployee(button.dataset.editPegawai);
						});
					});
				document
					.querySelectorAll("[data-delete-pegawai]")
					.forEach(function (button) {
						button.addEventListener("click", function () {
							deleteEmployee(button.dataset.deletePegawai);
						});
					});
			})
			.catch(function (error) {
				document.getElementById("table-pegawai").innerHTML =
					'<tr><td class="p-4 text-red-600" colspan="5">' +
					escapeHtml(error.message) +
					"</td></tr>";
			});
	}
	function loadRoles() {
		api("master/roles", null, "GET")
			.then(function (response) {
				roleRows = response.roles;
				document.getElementById("pegawai-role").innerHTML = roleRows
					.map(function (role) {
						return (
							'<option value="' +
							escapeHtml(role.kode) +
							'">' +
							escapeHtml(role.nama) +
							"</option>"
						);
					})
					.join("");
				var roleInput =
					document.querySelector(".menu-role-choice") ||
					document.getElementById("menu-role-admin");
				var roleBox = roleInput.parentElement.parentElement;
				roleBox.innerHTML =
					'<p class="font-semibold text-sm mb-2">Tampilkan untuk peran:</p>' +
					roleRows
						.map(function (role) {
							return (
								'<label class="mr-5"><input class="menu-role-choice" data-menu-role="' +
								escapeHtml(role.kode) +
								'" type="checkbox"> ' +
								escapeHtml(role.nama) +
								"</label>"
							);
						})
						.join("") +
					'<label class="ml-5"><input id="menu-active" type="checkbox" checked> Aktif</label>';
				document.getElementById("table-role").innerHTML = roleRows
					.map(function (role) {
						return (
							'<tr class="border-b"><td class="p-4">' +
							escapeHtml(role.nama) +
							"</td><td>" +
							escapeHtml(role.kode) +
							'</td><td class="p-4 text-center"><button class="text-blue-600 mr-3" data-edit-role="' +
							role.id +
							'"><i class="fa-solid fa-pen"></i> Edit</button><button class="text-red-600" data-delete-role="' +
							role.id +
							'"><i class="fa-solid fa-trash"></i> Hapus</button></td></tr>'
						);
					})
					.join("");
				document
					.querySelectorAll("[data-edit-role]")
					.forEach(function (button) {
						button.addEventListener("click", function () {
							showRoleForm(
								roleRows.filter(function (role) {
									return String(role.id) === button.dataset.editRole;
								})[0],
							);
						});
					});
				document
					.querySelectorAll("[data-delete-role]")
					.forEach(function (button) {
						button.addEventListener("click", function () {
							deleteRole(button.dataset.deleteRole);
						});
					});
			})
			.catch(function (error) {
				toast(error.message);
			});
	}
	function loadIncidentHistory() {
		return api("laporan/riwayat", null, "GET")
			.then(function (response) {
				var table = document.getElementById("table-riwayat-insiden");

				if (!table) {
					return response;
				}

				var laporan = response.laporan || [];

				table.innerHTML =
					laporan
						.map(function (report) {
							var statusClass =
								report.status === "selesai"
									? "bg-green-100 text-green-700"
									: report.status === "diproses"
										? "bg-yellow-100 text-yellow-700"
										: "bg-blue-100 text-blue-700";

							return (
								'<tr class="border-b">' +
								'<td class="p-4">' +
								escapeHtml(report.tanggal_kejadian) +
								"</td><td>" +
								escapeHtml(report.kategori) +
								"</td><td>" +
								escapeHtml(report.lokasi) +
								"</td><td>" +
								escapeHtml(report.pelapor) +
								'</td><td class="p-4">' +
								'<span class="badge ' +
								statusClass +
								'">' +
								escapeHtml(report.status) +
								"</span></td></tr>"
							);
						})
						.join("") ||
					'<tr><td class="p-4 text-gray-500" colspan="5">' +
						"Belum ada laporan insiden." +
						"</td></tr>";

				return response;
			})
			.catch(function (error) {
				var table = document.getElementById("table-riwayat-insiden");

				if (table) {
					table.innerHTML =
						'<tr><td class="p-4 text-red-600" colspan="5">' +
						escapeHtml(error.message) +
						"</td></tr>";
				}

				throw error;
			});
	}

	function loadChecklistHistory() {
		var table = document.getElementById("table-riwayat-checklist");

		if (!table) {
			return;
		}

		table.innerHTML =
			"<tr>" +
			'<td colspan="6" class="text-center text-gray-400 p-6">' +
			"Memuat riwayat checklist..." +
			"</td>" +
			"</tr>";

		return api("laporan/riwayat-checklist", null, "GET")
			.then(function (response) {
				var laporan = response.laporan || [];

				if (!laporan.length) {
					table.innerHTML =
						"<tr>" +
						'<td colspan="6" ' +
						'class="text-center text-gray-400 p-6">' +
						"Belum ada riwayat checklist." +
						"</td>" +
						"</tr>";

					return;
				}

				table.innerHTML = laporan
					.map(function (item) {
						var jumlahTidakSesuai =
							Number(item.total_item) - Number(item.jumlah_sesuai);

						return (
							'<tr class="border-b hover:bg-gray-50">' +
							'<td class="p-3">' +
							escapeHtml(item.tanggal_pengisian) +
							"</td>" +
							'<td class="p-3">' +
							escapeHtml(item.periode) +
							"</td>" +
							'<td class="p-3">' +
							escapeHtml(item.unit_kerja) +
							"</td>" +
							'<td class="p-3 text-center">' +
							'<span class="inline-flex px-2 py-1 ' +
							'rounded bg-green-100 text-green-700">' +
							item.jumlah_sesuai +
							"</span>" +
							"</td>" +
							'<td class="p-3 text-center">' +
							'<span class="inline-flex px-2 py-1 ' +
							'rounded bg-red-100 text-red-700">' +
							jumlahTidakSesuai +
							"</span>" +
							"</td>" +
							'<td class="p-3 text-center">' +
							"<button " +
							'type="button" ' +
							'class="px-3 py-1.5 rounded-lg ' +
							'bg-blue-600 text-white hover:bg-blue-700" ' +
							'data-checklist-detail="' +
							item.id +
							'">' +
							'<i class="fa-solid fa-eye mr-1"></i>' +
							"Detail" +
							"</button>" +
							"</td>" +
							"</tr>"
						);
					})
					.join("");

				table
					.querySelectorAll("[data-checklist-detail]")
					.forEach(function (button) {
						button.addEventListener("click", function () {
							loadChecklistDetail(this.dataset.checklistDetail);
						});
					});
			})
			.catch(function (error) {
				table.innerHTML =
					"<tr>" +
					'<td colspan="6" ' +
					'class="text-center text-red-600 p-6">' +
					escapeHtml(error.message) +
					"</td>" +
					"</tr>";
			});
	}
	function showEmployeeForm(user) {
		document.getElementById("pegawai-form").reset();
		document.getElementById("pegawai-id").value = user ? user.id : "";
		document.getElementById("pegawai-username").value = user
			? user.username
			: "";
		document.getElementById("pegawai-nama").value = user
			? user.nama_lengkap
			: "";
		document.getElementById("pegawai-unit").value = user ? user.unit_kerja : "";
		document.getElementById("pegawai-role").value = user ? user.role : "user";
		document.getElementById("pegawai-form-title").textContent = user
			? "Ubah Pegawai"
			: "Tambah Pegawai";
		document.getElementById("pegawai-form-wrapper").classList.remove("hidden");
	}
	function editEmployee(id) {
		showEmployeeForm(
			employeeRows.filter(function (user) {
				return String(user.id) === String(id);
			})[0],
		);
	}
	function deleteEmployee(id) {
		if (!window.confirm("Hapus pegawai ini?")) return;
		api("master/users/delete/" + id)
			.then(function (response) {
				toast(response.message);
				loadEmployees();
			})
			.catch(function (error) {
				toast(error.message);
			});
	}
	function setupEmployeeCrud() {
		document
			.getElementById("btn-tambah-pegawai")
			.addEventListener("click", function () {
				showEmployeeForm(null);
			});
		document
			.getElementById("btn-batal-pegawai")
			.addEventListener("click", function () {
				document.getElementById("pegawai-form-wrapper").classList.add("hidden");
			});
		document
			.getElementById("pegawai-form")
			.addEventListener("submit", function (event) {
				event.preventDefault();
				api("master/users", {
					id: document.getElementById("pegawai-id").value,
					username: document.getElementById("pegawai-username").value,
					nama_lengkap: document.getElementById("pegawai-nama").value,
					unit_kerja: document.getElementById("pegawai-unit").value,
					role: document.getElementById("pegawai-role").value,
					password: document.getElementById("pegawai-password").value,
				})
					.then(function (response) {
						toast(response.message);
						document
							.getElementById("pegawai-form-wrapper")
							.classList.add("hidden");
						loadEmployees();
					})
					.catch(function (error) {
						toast(error.message);
					});
			});
	}
	function masterList(jenis, elementId, icon) {
		api("master/" + jenis, null, "GET")
			.then(function (response) {
				document.getElementById(elementId).innerHTML =
					response.data
						.map(function (item) {
							return (
								'<li class="flex items-center justify-between gap-2 border-b pb-2"><span><i class="fa-solid ' +
								icon +
								' text-blue-500 mr-2"></i>' +
								escapeHtml(item.nama) +
								'</span><span class="whitespace-nowrap"><button class="text-blue-600 mr-2" data-edit-master="' +
								jenis +
								":" +
								item.id +
								'"><i class="fa-solid fa-pen"></i></button><button class="text-red-600" data-delete-master="' +
								jenis +
								":" +
								item.id +
								'"><i class="fa-solid fa-trash"></i></button></span></li>'
							);
						})
						.join("") || '<li class="text-gray-500">Belum ada data.</li>';
				response.data.forEach(function (item) {
					document
						.querySelector('[data-edit-master="' + jenis + ":" + item.id + '"]')
						.addEventListener("click", function () {
							showMasterForm(jenis, item);
						});
					document
						.querySelector(
							'[data-delete-master="' + jenis + ":" + item.id + '"]',
						)
						.addEventListener("click", function () {
							deleteMaster(jenis, item.id);
						});
				});
			})
			.catch(function (error) {
				document.getElementById(elementId).innerHTML =
					'<li class="text-red-600">' + escapeHtml(error.message) + "</li>";
			});
	}
	function loadMasterData() {
		masterList("kategori", "master-kategori", "fa-tag");
		masterList("unit", "master-unit", "fa-building");
		masterList("checklist", "master-checklist", "fa-check");
	}
	function showMasterForm(jenis, item) {
		document.getElementById("master-form").reset();
		document.getElementById("master-id").value = item ? item.id : "";
		document.getElementById("master-jenis").value = jenis;
		document.getElementById("master-nama").value = item ? item.nama : "";
		document.getElementById("master-form-title").textContent = item
			? "Ubah Data Master"
			: "Tambah Data Master";
		document.getElementById("master-form-wrapper").classList.remove("hidden");
	}
	function deleteMaster(jenis, id) {
		if (!window.confirm("Hapus data master ini?")) return;
		api("master/" + jenis + "/delete/" + id)
			.then(function (response) {
				toast(response.message);
				loadMasterData();
			})
			.catch(function (error) {
				toast(error.message);
			});
	}
	function setupMasterCrud() {
		document
			.querySelectorAll("[data-tambah-master]")
			.forEach(function (button) {
				button.addEventListener("click", function () {
					showMasterForm(button.dataset.tambahMaster);
				});
			});
		document
			.getElementById("btn-batal-master")
			.addEventListener("click", function () {
				document.getElementById("master-form-wrapper").classList.add("hidden");
			});
		document
			.getElementById("master-form")
			.addEventListener("submit", function (event) {
				event.preventDefault();
				var jenis = document.getElementById("master-jenis").value;
				api("master/" + jenis, {
					id: document.getElementById("master-id").value,
					nama: document.getElementById("master-nama").value,
				})
					.then(function (response) {
						toast(response.message);
						document
							.getElementById("master-form-wrapper")
							.classList.add("hidden");
						loadMasterData();
					})
					.catch(function (error) {
						toast(error.message);
					});
			});
	}
	function loadMenuManagement() {
		if (data.user.role !== "admin") return;
		api("master/menu", null, "GET")
			.then(function (response) {
				menuRows = response.menus;
				document.getElementById("table-menu").innerHTML =
					menuRows
						.map(function (menu) {
							return (
								'<tr class="border-b"><td class="p-4">' +
								menu.urutan +
								'</td><td><i class="fa-solid ' +
								escapeHtml(menu.icon) +
								' mr-2"></i>' +
								escapeHtml(menu.nama) +
								"</td><td>" +
								escapeHtml(menu.slug) +
								"</td><td>" +
								menu.roles
									.map(function (role) {
										return role === "admin" ? "Admin" : "Petugas";
									})
									.join(", ") +
								"</td><td>" +
								(Number(menu.is_active)
									? '<span class="badge">Aktif</span>'
									: '<span class="text-gray-500">Nonaktif</span>') +
								'</td><td class="p-4 text-center"><button class="text-blue-600 mr-3" data-edit-menu="' +
								menu.id +
								'"><i class="fa-solid fa-pen"></i> Edit</button><button class="text-red-600" data-delete-menu="' +
								menu.id +
								'"><i class="fa-solid fa-trash"></i> Hapus</button></td></tr>'
							);
						})
						.join("") ||
					'<tr><td class="p-4" colspan="6">Belum ada menu.</td></tr>';
				document
					.querySelectorAll("[data-edit-menu]")
					.forEach(function (button) {
						button.addEventListener("click", function () {
							showMenuForm(
								menuRows.filter(function (menu) {
									return String(menu.id) === button.dataset.editMenu;
								})[0],
							);
						});
					});
				document
					.querySelectorAll("[data-delete-menu]")
					.forEach(function (button) {
						button.addEventListener("click", function () {
							deleteMenu(button.dataset.deleteMenu);
						});
					});
			})
			.catch(function (error) {
				toast(error.message);
			});
	}
	function showMenuForm(menu) {
		populateMenuParents(menu);
		document.getElementById("menu-form").reset();
		document.getElementById("menu-id").value = menu ? menu.id : "";
		document.getElementById("menu-nama").value = menu ? menu.nama : "";
		document.getElementById("menu-slug").value = menu ? menu.slug : "";
		setMenuIcon(menu ? menu.icon : "fa-circle");
		document.getElementById("icon-picker").classList.add("hidden");
		document.getElementById("menu-urutan").value = menu ? menu.urutan : 0;
		document.querySelectorAll("[data-menu-role]").forEach(function (input) {
			input.checked = menu
				? menu.roles.indexOf(input.dataset.menuRole) !== -1
				: false;
		});
		document.getElementById("menu-active").checked = menu
			? Number(menu.is_active) === 1
			: true;
		document.getElementById("menu-form-title").textContent = menu
			? "Ubah Menu"
			: "Tambah Menu";
		document.getElementById("menu-form-wrapper").classList.remove("hidden");
	}
	function setMenuIcon(icon) {
		document.getElementById("menu-icon").value = icon;
		document.getElementById("menu-icon-preview").className = "fa-solid " + icon;
		document.querySelectorAll(".icon-choice").forEach(function (button) {
			button.classList.toggle("selected", button.dataset.icon === icon);
		});
	}
	function deleteMenu(id) {
		if (!window.confirm("Hapus menu ini?")) return;
		api("master/menu/delete/" + id)
			.then(function (response) {
				toast(response.message);
				loadMenuManagement();
				loadNavigation();
			})
			.catch(function (error) {
				toast(error.message);
			});
	}
	function setupMenuCrud() {
		document
			.getElementById("btn-tambah-menu")
			.addEventListener("click", function () {
				showMenuForm(null);
			});
		document
			.getElementById("btn-batal-menu")
			.addEventListener("click", function () {
				document.getElementById("menu-form-wrapper").classList.add("hidden");
			});
		document
			.getElementById("btn-pilih-icon")
			.addEventListener("click", function () {
				document.getElementById("icon-picker").classList.toggle("hidden");
			});
		document.querySelectorAll(".icon-choice").forEach(function (button) {
			button.addEventListener("click", function () {
				setMenuIcon(button.dataset.icon);
				document.getElementById("icon-picker").classList.add("hidden");
			});
		});
		document
			.getElementById("menu-form")
			.addEventListener("submit", function (event) {
				event.preventDefault();
				var roles = Array.prototype.slice
					.call(document.querySelectorAll("[data-menu-role]:checked"))
					.map(function (input) {
						return input.dataset.menuRole;
					});
				api("master/menu", {
					id: document.getElementById("menu-id").value,
					parent_id: document.getElementById("menu-parent-id").value,
					nama: document.getElementById("menu-nama").value,
					slug: document.getElementById("menu-slug").value,
					icon: document.getElementById("menu-icon").value,
					urutan: document.getElementById("menu-urutan").value,
					roles: roles,
					is_active: document.getElementById("menu-active").checked,
				})
					.then(function (response) {
						toast(response.message);
						document
							.getElementById("menu-form-wrapper")
							.classList.add("hidden");
						loadMenuManagement();
						loadNavigation();
					})
					.catch(function (error) {
						toast(error.message);
					});
			});
	}
	function populateMenuParents(menu) {
		var select = document.getElementById("menu-parent-id");
		var currentId = menu ? String(menu.id) : "";

		select.innerHTML =
			'<option value="">-- Menu Utama --</option>' +
			menuRows
				.filter(function (item) {
					return !item.parent_id && String(item.id) !== currentId;
				})
				.map(function (item) {
					return (
						'<option value="' +
						item.id +
						'">' +
						escapeHtml(item.nama) +
						"</option>"
					);
				})
				.join("");

		select.value = menu && menu.parent_id ? menu.parent_id : "";
	}
	function showRoleForm(role) {
		document.getElementById("role-form").reset();
		document.getElementById("role-id").value = role ? role.id : "";
		document.getElementById("role-nama").value = role ? role.nama : "";
		document.getElementById("role-kode").value = role ? role.kode : "";
		document.getElementById("role-form-title").textContent = role
			? "Ubah Peran"
			: "Tambah Peran";
		document.getElementById("role-form-wrapper").classList.remove("hidden");
	}
	function deleteRole(id) {
		if (!window.confirm("Hapus peran ini?")) return;
		api("master/roles/delete/" + id)
			.then(function (response) {
				toast(response.message);
				loadRoles();
			})
			.catch(function (error) {
				toast(error.message);
			});
	}
	function setupRoleCrud() {
		document
			.getElementById("btn-tambah-role")
			.addEventListener("click", function () {
				showRoleForm(null);
			});
		document
			.getElementById("btn-batal-role")
			.addEventListener("click", function () {
				document.getElementById("role-form-wrapper").classList.add("hidden");
			});
		document
			.getElementById("role-form")
			.addEventListener("submit", function (event) {
				event.preventDefault();
				api("master/roles", {
					id: document.getElementById("role-id").value,
					nama: document.getElementById("role-nama").value,
					kode: document.getElementById("role-kode").value,
				})
					.then(function (response) {
						toast(response.message);
						document
							.getElementById("role-form-wrapper")
							.classList.add("hidden");
						loadRoles();
					})
					.catch(function (error) {
						toast(error.message);
					});
			});
	}
	var loginForm = document.getElementById("login-form");
	if (loginForm) {
		loginForm.addEventListener("submit", function (event) {
			event.preventDefault();
			api("login", {
				username: document.getElementById("login-id").value,
				password: document.getElementById("login-pass").value,
			})
				.then(function () {
					window.location.assign(window.K3RS_HOME_URL);
				})
				.catch(function (error) {
					toast(error.message);
				});
		});
		document
			.getElementById("show-pass")
			.addEventListener("change", function (event) {
				document.getElementById("login-pass").type = event.target.checked
					? "text"
					: "password";
			});
		return;
	}
	setupEmployeeCrud();
	setupMasterCrud();
	setupMenuCrud();
	setupRoleCrud();
	render();
	loadMenuManagement();
	loadIncidentHistory();
	view("dashboard");
	document.getElementById("logout").addEventListener("click", function () {
		api("logout").finally(function () {
			window.location.assign(
				window.K3RS_HOME_URL.replace("dashboard", "login"),
			);
		});
	});
	document.querySelectorAll(".report-form").forEach(function (form) {
		form.addEventListener("submit", function (event) {
			event.preventDefault();
			var fields = form.querySelectorAll("input, select, textarea"),
				payload;
			if (form.dataset.type === "insiden")
				payload = {
					kategori: fields[0].value,
					tanggal: fields[1].value,
					lokasi: fields[2].value,
					kronologi: fields[3].value,
					tindakan: fields[4].value,
				};
			if (form.dataset.type === "kesehatan")
				payload = {
					nama: fields[1].value,
					diagnosa: fields[2].value,
					hari: fields[3].value,
				};
			if (form.dataset.type === "checklist") {
				var checklistItems = [];

				Array.prototype.slice
					.call(
						form.querySelectorAll(
							'#checklist-items input[type="radio"]:checked',
						),
					)
					.forEach(function (input) {
						checklistItems.push({
							checklist_id: input.name.replace("checklist-", ""),
							jawaban: input.value,
						});
					});

				// Pastikan seluruh item sudah dijawab
				var totalChecklist = form.querySelectorAll(
					'#checklist-items input[type="radio"][value="yes"]',
				).length;

				if (checklistItems.length !== totalChecklist) {
					toast("Silakan jawab semua item checklist terlebih dahulu.");
					return;
				}

				payload = {
					periode: fields[0].value,
					tanggal: fields[1].value,
					unit: fields[2].value,
					items: checklistItems,
				};
			}
			api("transaksi/" + form.dataset.type, payload)
				.then(function (response) {
					toast(response.message);
					form.reset();

					var lapSehatUnit = document.getElementById("lap-sehat-unit");

					if (lapSehatUnit) {
						lapSehatUnit.value = data.user.unit;
					}

					// Jika berhasil mengirim laporan insiden,
					// langsung arahkan ke menu Riwayat Insiden
					if (form.dataset.type === "insiden") {
						view("riwayat-insiden");
					}
				})
				.catch(function (error) {
					toast(error.message);
				});
		});
	});
})();
