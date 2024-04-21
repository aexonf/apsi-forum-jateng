import Layout from "@/components/elements/layout";
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar";
import { Button } from "@/components/ui/button";
import {
    Card,
    CardContent,
    CardFooter,
    CardHeader,
} from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Skeleton } from "@/components/ui/skeleton";
import { cn } from "@/lib/utils";
import { zodResolver } from "@hookform/resolvers/zod";
import axios from "axios";
import { EditIcon, LogOut, Pen } from "lucide-react";
import React, { useEffect, useRef, useState } from "react";
import { useForm } from "react-hook-form";
import { toast } from "sonner";
import { z } from "zod";

const formSchema = z.object({
    label: z.string().min(1, "Label harus di isi."),
    phone_number: z.string().min(1, "Nomor HP harus di isi."),
    email: z.string().email("Email harus valid").min(1, "Email harus di isi."),
});

export default function Home() {
    const token = localStorage.getItem("token");
    const {
        formState: { errors },
        reset,
        setValue,
    } = useForm({
        resolver: zodResolver(formSchema),
        defaultValues: {
            label: "",
            phone_number: "",
            email: "",
        },
    });

    async function fetchProfile() {
        setLoading(true);
        const response = await axios.get("/api/v1/user", {
            headers: {
                Authorization: `Bearer ${token}`,
            },
        });
        setProfile(response.data.data);
        setEditData(response.data.data);
        setLoading(false);
    }
    const [isEditing, setIsEditing] = useState(false);
    const [profile, setProfile] = useState(null);
    const [editData, setEditData] = useState(null);
    const [loading, setLoading] = useState(false);

    const fileInputRef = useRef(null);
    const [imagePreview, setImagePreview] = useState(null);

    const handleUploadClick = () => {
        fileInputRef.current.click();
    };
    const handleSubmit = async () => {
        setLoading(true);
        if (profile == editData) {
            toast.info("Tidak ada perubahan yang dilakukan.");
            setLoading(false);
            return;
        }
        try {
            const formData = new FormData();
            formData.append("image", editData.image);
            formData.append("name", editData.name);
            formData.append("email", editData.email);
            formData.append("label", editData.label);
            formData.append("phone_number", editData.phone_number);

            const response = await axios.post("/api/v1/user", formData, {
                headers: {
                    Authorization: `Bearer ${token}`,
                    "Content-Type": "multipart/form-data",
                },
            });
            toast.success(response.data.message);
            setLoading(false);
            fetchProfile();
        } catch (error) {
            console.error(error);
            toast.error(error.response.data.message);
            setLoading(false);
        }
    };

    useEffect(() => {
        fetchProfile();
    }, []);

    return (
        <Layout>
            <div className="flex items-center justify-between gap-3 my-4">
                <h3 className="scroll-m-20 text-2xl font-semibold tracking-tight">
                    Profil
                </h3>
                {isEditing ? (
                    <Button
                        onClick={() => {
                            setIsEditing(false);
                            handleSubmit();
                        }}
                    >
                        Simpan
                    </Button>
                ) : (
                    <Button onClick={() => setIsEditing(true)}>Ubah</Button>
                )}
            </div>
            <div className="w-full">
                {isEditing ? (
                    <Card>
                        <CardHeader />
                        <CardContent className="space-y-4">
                            <div className="flex items-center justify-center gap-4">
                                <div className="relative">
                                    {imagePreview ? (
                                        <Avatar className="size-40">
                                            <AvatarImage
                                                alt="profile"
                                                src={imagePreview}
                                            />
                                            <AvatarFallback>PV</AvatarFallback>
                                        </Avatar>
                                    ) : (
                                        profile?.img_url && (
                                            <Avatar
                                                className="size-40"
                                                onClick={handleUploadClick}
                                            >
                                                <AvatarImage
                                                    alt="profile"
                                                    src={`/storage/${profile?.img_url}`}
                                                />
                                                <AvatarFallback></AvatarFallback>
                                            </Avatar>
                                        )
                                    )}
                                    <input
                                        type="file"
                                        className="hidden"
                                        ref={fileInputRef}
                                        onChange={(e) => {
                                            setImagePreview(
                                                URL.createObjectURL(
                                                    e.target.files[0]
                                                )
                                            );
                                            setEditData({
                                                ...editData,
                                                image: e.target.files[0],
                                            });
                                        }}
                                    />
                                    <Button
                                        variant="ghost"
                                        size="icon"
                                        className="absolute right-0 bottom-0"
                                        onClick={handleUploadClick}
                                    >
                                        <Pen className="text-primary" />
                                    </Button>
                                </div>
                            </div>
                            <div className="space-y-2">
                                <Label htmlFor="email">Email</Label>
                                <Input
                                    defaultValue={editData?.email}
                                    id="email"
                                    placeholder="Masukkan email anda"
                                    type="email"
                                    required
                                    onChange={(e) =>
                                        setEditData({
                                            ...editData,
                                            email: e.target.value,
                                        })
                                    }
                                />
                            </div>
                            <div className="grid grid-cols-2 gap-4">
                                <div className="space-y-2">
                                    <Label htmlFor="phone">Phone Number</Label>
                                    <Input
                                        id="phone"
                                        defaultValue={editData?.phone_number}
                                        placeholder="Masukkan nomor telepon anda"
                                        type="number"
                                        onChange={(e) =>
                                            setEditData({
                                                ...editData,
                                                phone_number: e.target.value,
                                            })
                                        }
                                    />
                                </div>
                                <div className="space-y-2">
                                    <Label htmlFor="label">Label</Label>
                                    <Input
                                        id="label"
                                        defaultValue={editData?.phone_number}
                                        placeholder="Masukkan label"
                                        type="text"
                                        onChange={(e) =>
                                            setEditData({
                                                ...editData,
                                                label: e.target.value,
                                            })
                                        }
                                    />
                                </div>
                            </div>
                        </CardContent>
                        <CardFooter></CardFooter>
                    </Card>
                ) : (
                    <Card>
                        <CardHeader>
                            <div className="flex justify-end w-full"></div>
                        </CardHeader>

                        <CardContent className="flex justify-center items-center flex-col">
                            <div className="relative flex">
                                {loading ? (
                                    <Skeleton className="rounded-full size-40" />
                                ) : (
                                    <>
                                        {imagePreview ? (
                                            <img
                                                src={imagePreview}
                                                alt="preview"
                                                id="preview"
                                                className="size-40 bg-muted rounded-full object-cover"
                                            />
                                        ) : (
                                            profile?.img_url && (
                                                <img
                                                    src={`/storage/${profile?.img_url}`}
                                                    alt="profile"
                                                    id="preview"
                                                    className="size-40 bg-muted rounded-full"
                                                />
                                            )
                                        )}
                                    </>
                                )}
                            </div>
                            <div
                                className={cn(
                                    "my-4 flex items-center flex-col",
                                    loading ? "gap-y-4" : "gap-y-2"
                                )}
                            />
                        </CardContent>

                        <CardFooter className="flex-col items-start">
                            <div className="my-2">
                                <h4 className="scroll-m-20 text-xl tracking-tight">
                                    Nama
                                </h4>
                                {loading ? (
                                    <Skeleton className="h-5 w-[250px]" />
                                ) : (
                                    <h4 className="scroll-m-20 text-xl font-semibold tracking-tight">
                                        {profile?.name}
                                    </h4>
                                )}
                            </div>
                            <div className="my-2">
                                <h4 className="scroll-m-20 text-xl tracking-tight">
                                    Label
                                </h4>
                                {loading ? (
                                    <Skeleton className="h-5 w-[250px]" />
                                ) : (
                                    <h5 className="scroll-m-20 text-lg font-semibold tracking-tight">
                                        {profile?.label}
                                    </h5>
                                )}
                            </div>
                            <div className="my-2">
                                <h4 className="scroll-m-20 text-xl tracking-tight">
                                    NIP
                                </h4>
                                {loading ? (
                                    <Skeleton className="h-5 w-[250px]" />
                                ) : (
                                    <h4 className="scroll-m-20 text-xl font-bold tracking-tight">
                                        {profile?.id_number}
                                    </h4>
                                )}
                            </div>
                            <div className="my-2">
                                <h4 className="scroll-m-20 text-xl tracking-tight">
                                    Email
                                </h4>
                                {loading ? (
                                    <Skeleton className="h-5 w-[250px]" />
                                ) : (
                                    <h4 className="scroll-m-20 text-xl font-bold tracking-tight">
                                        {profile?.email}
                                    </h4>
                                )}
                            </div>
                            <div className="my-2">
                                <h4 className="scroll-m-20 text-xl tracking-tight">
                                    No. HP
                                </h4>
                                {loading ? (
                                    <Skeleton className="h-5 w-[250px]" />
                                ) : (
                                    <h4 className="scroll-m-20 text-xl font-bold tracking-tight">
                                        {profile?.phone_number}
                                    </h4>
                                )}
                            </div>
                            <div className="my-2">
                                <h4 className="scroll-m-20 text-xl tracking-tight">
                                    Tingkat
                                </h4>
                                {loading ? (
                                    <Skeleton className="h-5 w-[250px]" />
                                ) : (
                                    <h4 className="scroll-m-20 text-xl font-bold tracking-tight">
                                        {profile?.level}
                                    </h4>
                                )}
                            </div>
                        </CardFooter>
                    </Card>
                )}
            </div>
            <Button
                className="w-full my-4 gap-4"
                variant="destructive"
                size="lg"
            >
                <LogOut className="size-5" strokeWidth={3} />
                <span className="font-semibold">Keluar</span>
            </Button>
        </Layout>
    );
}
